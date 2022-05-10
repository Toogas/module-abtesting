<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * AbTest Interface
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */
namespace Toogas\AbTesting\Api\Data;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
interface AbTestInterface
{

    public const BLOCK_2 = 'block_2';
    public const ENTITY_ID = 'entity_id';
    public const BLOCK_1 = 'block_1';
    public const ACTIVE = 'active';
    public const TITLE = 'title';
    public const BLOCK_1_RENDER_COUNT = 'block_1_render_count';
    public const BLOCK_2_RENDER_COUNT = 'block_2_render_count';
    public const START_DATE = 'start_date';
    public const END_DATE = 'end_date';

    /**
     * Get entity_id
     *
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     *
     * @param string $entityId
     * @return AbTestInterface
     */
    public function setEntityId($entityId);

    /**
     * Get active
     *
     * @return bool|null
     */
    public function getActive();

    /**
     * Set active
     *
     * @param bool $active
     * @return AbTestInterface
     */
    public function setActive($active);

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return AbTestInterface
     */
    public function setTitle($title);

    /**
     * Get block_1
     *
     * @return string|null
     */
    public function getBlock1();

    /**
     * Set block_1
     *
     * @param string $block1
     * @return AbTestInterface
     */
    public function setBlock1($block1);

    /**
     * Get block_2
     *
     * @return string|null
     */
    public function getBlock2();

    /**
     * Set block_2
     *
     * @param string $block2
     * @return AbTestInterface
     */
    public function setBlock2($block2);

    /**
     * Get block_1_render_count
     *
     * @return string|null
     */
    public function getBlock1RenderCount();

    /**
     * Set block_1_render_count
     *
     * @param string $count
     * @return AbTestInterface
     */
    public function setBlock1RenderCount($count);

    /**
     * Get block_2_render_count
     *
     * @return string|null
     */
    public function getBlock2RenderCount();

    /**
     * Set block_2_render_count
     *
     * @param string $count
     * @return AbTestInterface
     */
    public function setBlock2RenderCount($count);

    /**
     * Get start_date
     *
     * @return string|null
     */
    public function getStartDate();

    /**
     * Set start_date
     *
     * @param string $date
     * @return AbTestInterface
     */
    public function setStartDate($date);

    /**
     * Get end_date
     *
     * @return string|null
     */
    public function getEndDate();

    /**
     * Set end_date
     *
     * @param string $date
     * @return AbTestInterface
     */
    public function setEndDate($date);
}
