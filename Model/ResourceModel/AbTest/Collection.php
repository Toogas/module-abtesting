<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * Collection Class
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */
namespace Toogas\AbTesting\Model\ResourceModel\AbTest;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class Collection extends AbstractCollection
{

    /**
     * @var string $_idFieldName
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Toogas\AbTesting\Model\AbTest::class,
            \Toogas\AbTesting\Model\ResourceModel\AbTest::class
        );
    }
}
