<?php
/**
 * AbTest Class - Provides the list of saved AbTests
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */

namespace Toogas\AbTesting\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Toogas\AbTesting\Api\AbTestRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class AbTest implements OptionSourceInterface
{

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var AbTestRepositoryInterface
     */
    protected $abTestRepository;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * AbTest Constructor
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AbTestRepositoryInterface $abTestRepository
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AbTestRepositoryInterface $abTestRepository,
        FilterBuilder $filterBuilder
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->abTestRepository = $abTestRepository;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray()
    {
        $filter = $this->filterBuilder
            ->setField('active')
            ->setConditionType('eq')
            ->setValue(1)
            ->create();
        $this->searchCriteriaBuilder->addFilters([$filter]);
        $criteria = $this->searchCriteriaBuilder->create();
        $abTests = $this->abTestRepository->getList($criteria)->getItems();
        $data = [];
        foreach ($abTests as $abTest) {
            $data[] = [
                'label' => $abTest->getTitle(),
                'value' => $abTest->getEntityId()
            ];
        }
        return $data;
    }
}
