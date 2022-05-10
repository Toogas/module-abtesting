<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * Statistics Class
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
use Magento\Framework\Exception\LocalizedException;
use Toogas\AbTesting\Api\StatisticRepositoryInterface;
use Toogas\AbTesting\Api\Data\StatisticInterfaceFactory;
use Magento\Customer\Model\Session;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class Statistics extends Action implements HttpGetActionInterface
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
     * @param Context $context
     * @param StatisticRepositoryInterface $statisticRepository
     * @param StatisticInterfaceFactory $statisticFactory
     * @param Session $session
     */
    public function __construct(
        Context $context,
        StatisticRepositoryInterface $statisticRepository,
        StatisticInterfaceFactory $statisticFactory,
        Session $session
    ) {
        $this->statisticRepository = $statisticRepository;
        $this->statisticFactory = $statisticFactory;
        $this->session = $session;
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
            'message' => ''
        ];

        try {
            $id = $this->getRequest()->getParam('id');
            $block = $this->getRequest()->getParam('block');
            $action = $this->getRequest()->getParam('action');
            if ($id) {
                switch ($action) {
                    case 'countView':
                        $this->_countView($id, $block);
                        break;
                    case 'countClick':
                        $this->_countClick($id, $block);
                        break;
                }
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

    /**
     * Count views per content
     *
     * @param string $id
     * @param string $block
     * @throws LocalizedException
     */
    protected function _countView(string $id, string $block)
    {
        $stat = $this->statisticFactory->create();
        $stat->setSessionId($this->session->getSessionId());
        $stat->setEmail($this->session->getCustomer() ? $this->session->getCustomer()->getEmail() : '');
        $stat->setTestId($id);
        $stat->setContent($block);
        $stat->setAction('view');
        $this->statisticRepository->save($stat);
    }

    /**
     * Count clicks per content
     *
     * @param string $id
     * @param string $block
     * @throws LocalizedException
     */
    protected function _countClick(string $id, string $block)
    {
        $stat = $this->statisticFactory->create();
        $stat->setSessionId($this->session->getSessionId());
        $stat->setEmail($this->session->getCustomer() ? $this->session->getCustomer()->getEmail() : '');
        $stat->setTestId($id);
        $stat->setContent($block);
        $stat->setAction('click');
        $this->statisticRepository->save($stat);
    }
}
