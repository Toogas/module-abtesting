<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * AbTest Class
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */
namespace Toogas\AbTesting\Block\Widget;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Toogas\AbTesting\Model\AbTestRepository;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class AbTest extends Template implements BlockInterface
{

    /**
     * @var string
     */
    protected $_template = "widget/abtest.phtml";

    /**
     * @var AbTestRepository
     */
    protected $abTestRepository;

    /**
     * @param AbTestRepository $abTestRepository
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        AbTestRepository $abTestRepository,
        Template\Context $context,
        array $data = []
    ) {
        $this->abTestRepository = $abTestRepository;
        parent::__construct($context, $data);
    }

    /**
     * Render block HTML
     *
     * @return string
     * @throws ValidatorException|LocalizedException
     */
    protected function _toHtml()
    {
        $id = $this->getData('entity_id');
        if (!$id) {
            return '';
        }

        try {
            $model = $this->abTestRepository->get($id);
            if (!$model->getActive()) {
                return '';
            }
        } catch (\Exception $e) {
            /**
             * Escape quietly if test does not exist
             */
        }

        if (!$this->getTemplate()) {
            return '';
        }

        return $this->fetchView($this->getTemplateFile());
    }
}
