<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * OrderPlaceAfter Class
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */
namespace Toogas\AbTesting\Observer\Frontend\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Toogas\AbTesting\Api\StatisticRepositoryInterface;
use Toogas\AbTesting\Api\Data\StatisticInterfaceFactory;
use Magento\Customer\Model\Session;
use Magento\Checkout\Model\Session as CheckoutSession;
use Toogas\AbTesting\Model\ResourceModel\Statistic\CollectionFactory as StatisticCollection;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class OrderSaveAfter implements ObserverInterface
{

    /**
     * @var StatisticRepositoryInterface
     */
    protected $statisticRepository;

    /**
     * @var StatisticInterfaceFactory
     */
    protected $statisticFactory;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var StatisticCollection
     */
    protected $statisticCollection;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * OrderPlaceAfter constructor
     *
     * @param StatisticRepositoryInterface $statisticRepository
     * @param StatisticInterfaceFactory $statisticFactory
     * @param Session $session
     * @param StatisticCollection $statisticCollection
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        StatisticRepositoryInterface $statisticRepository,
        StatisticInterfaceFactory $statisticFactory,
        Session $session,
        StatisticCollection $statisticCollection,
        CheckoutSession $checkoutSession
    ) {
        $this->statisticRepository = $statisticRepository;
        $this->statisticFactory = $statisticFactory;
        $this->session = $session;
        $this->statisticCollection = $statisticCollection;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(
        Observer $observer
    ) {
        //Check if user has already seen any content and if soo add statistic of checkout
        $order = $observer->getOrder();

        if (!$order->getId()) {
            return;
        }

        $sessionId = $this->session->getSessionId();
        $existingViews = $this->statisticCollection->create()
            ->addFieldToFilter('action', 'view')
            ->addFieldToFilter('session_id', $sessionId);
        $existingViews->getSelect()->group('test_id');

        if ($existingViews->getSize() > 0) {
            foreach ($existingViews->getItems() as $view) {
                $checkExists = $this->statisticCollection->create()
                    ->addFieldToFilter('action', 'checkout')
                    ->addFieldToFilter('test_id', $view->getTestId())
                    ->addFieldToFilter('order_id', $order->getId())
                    ->addFieldToFilter('session_id', $sessionId);
                if (!$checkExists->getSize()) {
                    $stat = $this->statisticFactory->create();
                    $stat->setSessionId($sessionId);
                    $stat->setEmail($order->getCustomerEmail());
                    $stat->setTestId($view->getTestId());
                    $stat->setContent($view->getContent());
                    $stat->setAction('checkout');
                    $stat->setSaleValue($order->getGrandTotal());
                    $stat->setOrderId($order->getId());
                    $this->statisticRepository->save($stat);
                }
            }
        }
    }
}
