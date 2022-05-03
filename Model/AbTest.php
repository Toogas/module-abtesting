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
namespace Toogas\AbTesting\Model;

use Magento\Framework\Model\AbstractModel;
use Toogas\AbTesting\Api\Data\AbTestInterface;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class AbTest extends AbstractModel implements AbTestInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Toogas\AbTesting\Model\ResourceModel\AbTest::class);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function getActive()
    {
        return $this->getData(self::ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setActive($active)
    {
        return $this->setData(self::ACTIVE, $active);
    }

    /**
     * @inheritDoc
     */
    public function getBlock1()
    {
        return $this->getData(self::BLOCK_1);
    }

    /**
     * @inheritDoc
     */
    public function setBlock1($block1)
    {
        return $this->setData(self::BLOCK_1, $block1);
    }

    /**
     * @inheritDoc
     */
    public function getBlock2()
    {
        return $this->getData(self::BLOCK_2);
    }

    /**
     * @inheritDoc
     */
    public function setBlock2($block2)
    {
        return $this->setData(self::BLOCK_2, $block2);
    }

    /**
     * @inheritDoc
     */
    public function getBlock1RenderCount()
    {
        return $this->getData(self::BLOCK_1_RENDER_COUNT);
    }

    /**
     * @inheritDoc
     */
    public function setBlock1RenderCount($count)
    {
        return $this->setData(self::BLOCK_1_RENDER_COUNT, $count);
    }

    /**
     * @inheritDoc
     */
    public function getBlock2RenderCount()
    {
        return $this->getData(self::BLOCK_2_RENDER_COUNT);
    }

    /**
     * @inheritDoc
     */
    public function setBlock2RenderCount($count)
    {
        return $this->setData(self::BLOCK_2_RENDER_COUNT, $count);
    }

    /**
     * @inheritDoc
     */
    public function getStartDate()
    {
        return $this->getData(self::START_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setStartDate($date)
    {
        return $this->setData(self::START_DATE, $date);
    }

    /**
     * @inheritDoc
     */
    public function getEndDate()
    {
        return $this->getData(self::END_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setEndDate($date)
    {
        return $this->setData(self::END_DATE, $date);
    }
}
