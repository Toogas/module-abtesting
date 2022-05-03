<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/**
 * AbTestRepository Class
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
use Toogas\AbTesting\Api\AbTestRepositoryInterface;
use Toogas\AbTesting\Api\Data\AbTestInterface;
use Toogas\AbTesting\Api\Data\AbTestInterfaceFactory;
use Toogas\AbTesting\Api\Data\AbTestSearchResultsInterfaceFactory;
use Toogas\AbTesting\Model\ResourceModel\AbTest as ResourceAbTest;
use Toogas\AbTesting\Model\ResourceModel\AbTest\CollectionFactory as AbTestCollectionFactory;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class AbTestRepository implements AbTestRepositoryInterface
{

    /**
     * @var AbTestInterfaceFactory
     */
    protected $abTestFactory;

    /**
     * @var AbTest
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var ResourceAbTest
     */
    protected $resource;

    /**
     * @var AbTestCollectionFactory
     */
    protected $abTestCollectionFactory;

    /**
     * @param ResourceAbTest $resource
     * @param AbTestInterfaceFactory $abTestFactory
     * @param AbTestCollectionFactory $abTestCollectionFactory
     * @param AbTestSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceAbTest $resource,
        AbTestInterfaceFactory $abTestFactory,
        AbTestCollectionFactory $abTestCollectionFactory,
        AbTestSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->abTestFactory = $abTestFactory;
        $this->abTestCollectionFactory = $abTestCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(AbTestInterface $abTest)
    {
        try {
            $this->resource->save($abTest);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the abTest: %1',
                $exception->getMessage()
            ));
        }
        return $abTest;
    }

    /**
     * @inheritDoc
     */
    public function get($entityId)
    {
        $abTest = $this->abTestFactory->create();
        $this->resource->load($abTest, $entityId);
        if (!$abTest->getId()) {
            throw new NoSuchEntityException(__('AbTest with id "%1" does not exist.', $entityId));
        }
        return $abTest;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->abTestCollectionFactory->create();

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
    public function delete(AbTestInterface $abTest)
    {
        try {
            $abTestModel = $this->abTestFactory->create();
            $this->resource->load($abTestModel, $abTest->getEntityId());
            $this->resource->delete($abTestModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the AbTest: %1',
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
