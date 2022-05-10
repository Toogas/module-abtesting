<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * StatisticRepository Interface
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */
namespace Toogas\AbTesting\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
interface StatisticRepositoryInterface
{

    /**
     * Save Statistic
     *
     * @param \Toogas\AbTesting\Api\Data\StatisticInterface $statistic
     * @return \Toogas\AbTesting\Api\Data\StatisticInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Toogas\AbTesting\Api\Data\StatisticInterface $statistic
    );

    /**
     * Retrieve Statistic
     *
     * @param string $entityId
     * @return \Toogas\AbTesting\Api\Data\StatisticInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($entityId);

    /**
     * Retrieve Statistic matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Toogas\AbTesting\Api\Data\StatisticSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Statistic
     *
     * @param \Toogas\AbTesting\Api\Data\StatisticInterface $statistic
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Toogas\AbTesting\Api\Data\StatisticInterface $statistic
    );

    /**
     * Delete Statistic by ID
     *
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
