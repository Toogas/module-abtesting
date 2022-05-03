<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * StatisticRepository Class
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */
namespace Toogas\AbTesting\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Toogas\AbTesting\Api\Data\StatisticInterface;
use Toogas\AbTesting\Api\Data\StatisticInterfaceFactory;
use Toogas\AbTesting\Api\Data\StatisticSearchResultsInterfaceFactory;
use Toogas\AbTesting\Api\StatisticRepositoryInterface;
use Toogas\AbTesting\Model\ResourceModel\Statistic as ResourceStatistic;
use Toogas\AbTesting\Model\ResourceModel\Statistic\CollectionFactory as StatisticCollectionFactory;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class StatisticRepository implements StatisticRepositoryInterface
{

    /**
     * @var ResourceStatistic
     */
    protected $resource;

    /**
     * @var StatisticInterfaceFactory
     */
    protected $statisticFactory;

    /**
     * @var StatisticCollectionFactory
     */
    protected $statisticCollectionFactory;

    /**
     * @var Statistic
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @param ResourceStatistic $resource
     * @param StatisticInterfaceFactory $statisticFactory
     * @param StatisticCollectionFactory $statisticCollectionFactory
     * @param StatisticSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceStatistic $resource,
        StatisticInterfaceFactory $statisticFactory,
        StatisticCollectionFactory $statisticCollectionFactory,
        StatisticSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->statisticFactory = $statisticFactory;
        $this->statisticCollectionFactory = $statisticCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(StatisticInterface $statistic)
    {
        try {
            $this->resource->save($statistic);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the statistic: %1',
                $exception->getMessage()
            ));
        }
        return $statistic;
    }

    /**
     * @inheritDoc
     */
    public function get($entityId)
    {
        $statistic = $this->statisticFactory->create();
        $this->resource->load($statistic, $entityId);
        if (!$statistic->getId()) {
            throw new NoSuchEntityException(__('Statistic with id "%1" does not exist.', $entityId));
        }
        return $statistic;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->statisticCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(StatisticInterface $statistic)
    {
        try {
            $statisticModel = $this->statisticFactory->create();
            $this->resource->load($statisticModel, $statistic->getEntityId());
            $this->resource->delete($statisticModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Statistic: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->get($entityId));
    }
}
