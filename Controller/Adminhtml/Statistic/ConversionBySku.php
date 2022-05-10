<?php

namespace Toogas\AbTesting\Controller\Adminhtml\Statistic;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Json\Helper\Data;
use Magento\Sales\Model\OrderRepository;
use Toogas\AbTesting\Model\ResourceModel\Statistic\CollectionFactory as StatisticCollection;

class ConversionBySku extends \Magento\Backend\App\Action
{

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @var StatisticCollection
     */
    protected $statisticCollection;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * constructor
     *
     * @param Context $context
     * @param Data $jsonHelper
     * @param StatisticCollection $statisticCollection
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        StatisticCollection $statisticCollection,
        OrderRepository $orderRepository
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->statisticCollection = $statisticCollection;
        $this->orderRepository = $orderRepository;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $count = [
            'error' => false,
            'message' => '',
            'a' => 0,
            'b' => 0
        ];
        $ordersChecked = [];
        $test = $this->getRequest()->getParam('test');
        $sku = $this->getRequest()->getParam('sku');

        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $test)
            ->addFieldToFilter('action', 'checkout');

        foreach ($collection->getItems() as $checkout) {
            $orderId = $checkout->getData('order_id');
            if ($orderId && !in_array($orderId, $ordersChecked)) {
                $ordersChecked[] = $orderId;
                $count = $this->getOrderItemsCount($count, $orderId, $checkout, $sku);
            }
        }

        if ($count['error']) {
            $return = [
                'error' => true,
                'message' => __('Sorry, something went wrong.')
            ];
        } else {
            $viewsA = $this->getContentAUniqueViews($test);
            $viewsB = $this->getContentBUniqueViews($test);

            $return = [
                'error' => false,
                'a' => $viewsA ? (($count['a'] / $viewsA) * 100) : 0,
                'b' => $viewsB ? (($count['b'] / $viewsB) * 100) : 0
            ];
        }

        return $this->jsonResponse($return);
    }

    /**
     * Check items for given order to check if sku was bought
     *
     * @param array $count
     * @param string $orderId
     * @param DataObject $checkout
     * @param string $sku
     * @return mixed
     */
    public function getOrderItemsCount($count, $orderId, $checkout, $sku)
    {
        try {
            $order = $this->orderRepository->get($orderId);
            foreach ($order->getItems() as $item) {
                if ($item->getSku() == $sku) {
                    if ($checkout->getData('content') == 2) {
                        $count['b']++;
                    } else {
                        $count['a']++;
                    }
                }
            }
        } catch (\Exception $e) {
            $count['error'] = true;
            $count['message'] = $e->getMessage();
        }

        return $count;
    }

    /**
     * Get content A number of unique views
     *
     * @param string $test
     * @return int
     */
    public function getContentAUniqueViews($test)
    {
        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $test)
            ->addFieldToFilter('content', 1)
            ->addFieldToFilter('action', 'view');
        $collection->getSelect()->group('session_id');
        return $collection->count();
    }

    /**
     * Get content B number of unique views
     *
     * @param string $test
     * @return int
     */
    public function getContentBUniqueViews($test)
    {
        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $test)
            ->addFieldToFilter('content', 2)
            ->addFieldToFilter('action', 'view');
        $collection->getSelect()->group('session_id');
        return $collection->count();
    }

    /**
     * Create json response
     *
     * @param mixed $response
     * @return ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
