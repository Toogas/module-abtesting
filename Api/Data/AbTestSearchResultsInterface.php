<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * AbTestSearchResults Interface
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
interface AbTestSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get AbTest list.
     *
     * @return \Toogas\AbTesting\Api\Data\AbTestInterface[]
     */
    public function getItems();

    /**
     * Set block_1 list.
     *
     * @param \Toogas\AbTesting\Api\Data\AbTestInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
