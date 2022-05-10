<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * Content Class
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */
namespace Toogas\AbTesting\Controller\Frontend;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Toogas\AbTesting\Api\AbTestRepositoryInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Toogas\AbTesting\Model\ResourceModel\Statistic\CollectionFactory as StatisticCollection;
use function GuzzleHttp\Psr7\str;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class Content extends Action implements HttpGetActionInterface
{

    /**
     * @var AbTestRepositoryInterface
     */
    protected $abTestRepository;

    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var StatisticCollection
     */
    protected $statisticCollection;

    /**
     * @param Context $context
     * @param AbTestRepositoryInterface $abTestRepository
     * @param FilterProvider $filterProvider
     * @param StoreManagerInterface $storeManager
     * @param Session $session
     * @param StatisticCollection $statisticCollection
     */
    public function __construct(
        Context $context,
        AbTestRepositoryInterface $abTestRepository,
        FilterProvider $filterProvider,
        StoreManagerInterface $storeManager,
        Session $session,
        StatisticCollection $statisticCollection
    ) {
        $this->abTestRepository = $abTestRepository;
        $this->filterProvider = $filterProvider;
        $this->storeManager = $storeManager;
        $this->session = $session;
        $this->statisticCollection = $statisticCollection;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return Json
     */
    public function execute()
    {
        $responseData = [
            'error' => false,
            'html' => '',
            'block' => '',
            'message' => ''
        ];

        try {
            $id = $this->getRequest()->getParam('id');
            $block = $this->getRequest()->getParam('block');
            $storeId = $this->storeManager->getStore()->getId();
            if ($id) {
                $test = $this->abTestRepository->get($id);

                //Check if user has already seen any content and if soo keep seeing the same content
                $existingView = $this->statisticCollection->create()
                    ->addFieldToFilter('test_id', $id)
                    ->addFieldToFilter('action', 'view')
                    ->addFieldToFilter('session_id', $this->session->getSessionId())
                    ->getFirstItem();
                if ($existingView && $lastBlock = $existingView->getContent()) {
                    $block = $lastBlock;
                }

                /** Control render by start and end date */
                $now = strtotime(date("Y-m-d H:i:s"));
                $startDate = $test->getStartDate();
                $endDate = $test->getEndDate();
                $allowRender = true;
                if ($startDate && strtotime($startDate) > $now) {
                    $allowRender = false;
                }
                if ($endDate && strtotime($endDate) < $now) {
                    $allowRender = false;
                }

                if ($allowRender) {
                    if ($block == 1) {
                        $html = $this->filterProvider->getBlockFilter()
                            ->setStoreId($storeId)
                            ->filter($test->getBlock1());
                        $responseData['html'] = $html;
                        $count = $test->getBlock1RenderCount();
                        $test->setBlock1RenderCount($count + 1);
                    } else {
                        $html = $this->filterProvider->getBlockFilter()
                            ->setStoreId($storeId)
                            ->filter($test->getBlock2());
                        $responseData['html'] = $html;
                        $count = $test->getBlock2RenderCount();
                        $test->setBlock2RenderCount($count + 1);
                    }
                }

                $responseData['block'] = $block;
                $this->abTestRepository->save($test);
            } else {
                $responseData['error'] = true;
                $responseData['message'] = __('No id provided');
            }
        } catch (\Exception $e) {
            $responseData['error'] = true;
            $responseData['message'] = $e->getMessage();
        }

        /** @var Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($responseData);
        return $resultJson;
    }
}
