<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * Statistic Interface
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
interface StatisticInterface
{

    public const SESSION_ID = 'session_id';
    public const ENTITY_ID = 'entity_id';
    public const EMAIL = 'email';
    public const TEST_ID = 'test_id';
    public const CONTENT = 'content';
    public const ACTION = 'action';
    public const SALE_VALUE = 'sale_value';
    public const ORDER_ID = 'order_id';

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
     * @return StatisticInterface
     */
    public function setEntityId($entityId);

    /**
     * Get session_id
     *
     * @return string|null
     */
    public function getSessionId();

    /**
     * Set session_id
     *
     * @param string $sessionId
     * @return StatisticInterface
     */
    public function setSessionId($sessionId);

    /**
     * Get email
     *
     * @return string|null
     */
    public function getEmail();

    /**
     * Set email
     *
     * @param string $email
     * @return StatisticInterface
     */
    public function setEmail($email);

    /**
     * Get test_id
     *
     * @return int|null
     */
    public function getTestId();

    /**
     * Set test_id
     *
     * @param int $testId
     * @return StatisticInterface
     */
    public function setTestId($testId);

    /**
     * Get content
     *
     * @return int|null
     */
    public function getContent();

    /**
     * Set content
     *
     * @param int $content
     * @return StatisticInterface
     */
    public function setContent($content);

    /**
     * Get action
     *
     * @return string|null
     */
    public function getAction();

    /**
     * Set action
     *
     * @param string $action
     * @return StatisticInterface
     */
    public function setAction($action);

    /**
     * Get sale_value
     *
     * @return float|null
     */
    public function getSaleValue();

    /**
     * Set order_id
     *
     * @param float $saleValue
     * @return StatisticInterface
     */
    public function setSaleValue($saleValue);

    /**
     * Get order_id
     *
     * @return int|null
     */
    public function getOrderId();

    /**
     * Set order_id
     *
     * @param int $orderId
     * @return StatisticInterface
     */
    public function setOrderId($orderId);
}
