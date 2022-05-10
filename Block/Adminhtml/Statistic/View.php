<?php

/**
 * View Class
 * PHP Version 7.4
 *
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 * @since    1.0.0
 */
namespace Toogas\AbTesting\Block\Adminhtml\Statistic;

use Magento\Backend\Block\Template;
use Toogas\AbTesting\Model\ResourceModel\Statistic\CollectionFactory as StatisticCollection;
use Toogas\AbTesting\Model\ResourceModel\AbTest\CollectionFactory as TestCollection;

/**
 * @author   Toogas Team <comercial@toogas.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License OSL3.0
 * @link     http://toogas.com
 */
class View extends Template
{

    /**
     * @var StatisticCollection
     */
    protected $statisticCollection;

    /**
     * @var TestCollection
     */
    protected $testCollection;

    /**
     * @var $_dailyViews array
     */
    protected $_dailyViews;

    /**
     * View constructor
     *
     * @param StatisticCollection $statisticCollection
     * @param TestCollection $testCollection
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        StatisticCollection $statisticCollection,
        TestCollection $testCollection,
        Template\Context $context,
        array $data = []
    ) {
        $this->statisticCollection = $statisticCollection;
        $this->testCollection = $testCollection;
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Get content A render count
     *
     * @return int
     */
    public function getContentARenderCount()
    {
        $id = $this->getRequest()->getParam('entity_id');
        return $this->testCollection->create()
            ->addFieldToFilter('entity_id', $id)
            ->getFirstItem()
            ->getBlock1RenderCount();
    }

    /**
     * Get content A number of views
     *
     * @return int
     */
    public function getContentAViews()
    {
        $id = $this->getRequest()->getParam('entity_id');
        return $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 1)
            ->addFieldToFilter('action', 'view')
            ->count();
    }

    /**
     * Get content A number of unique views
     *
     * @return int
     */
    public function getContentAUniqueViews()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 1)
            ->addFieldToFilter('action', 'view');
        $collection->getSelect()->group('session_id');
        return $collection->count();
    }

    /**
     * Get content B number of unique views
     *
     * @return int
     */
    public function getContentBUniqueViews()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 2)
            ->addFieldToFilter('action', 'view');
        $collection->getSelect()->group('session_id');
        return $collection->count();
    }

    /**
     * Get content B render count
     *
     * @return int
     */
    public function getContentBRenderCount()
    {
        $id = $this->getRequest()->getParam('entity_id');
        return $this->testCollection->create()
            ->addFieldToFilter('entity_id', $id)
            ->getFirstItem()
            ->getBlock2RenderCount();
    }

    /**
     * Get content B number of views
     *
     * @return int
     */
    public function getContentBViews()
    {
        $id = $this->getRequest()->getParam('entity_id');
        return $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 2)
            ->addFieldToFilter('action', 'view')
            ->count();
    }

    /**
     * Get content A click count
     *
     * @return int
     */
    public function getContentAClicks()
    {
        $id = $this->getRequest()->getParam('entity_id');
        return $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 1)
            ->addFieldToFilter('action', 'click')
            ->count();
    }

    /**
     * Get content B click count
     *
     * @return int
     */
    public function getContentBClicks()
    {
        $id = $this->getRequest()->getParam('entity_id');
        return $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 2)
            ->addFieldToFilter('action', 'click')
            ->count();
    }

    /**
     * Get content A Overall Score
     *
     * @return int
     */
    public function getContentAOverall()
    {
        $views = $this->getContentAViews();
        $clicks = $this->getContentAClicks();
        $rate = $this->getConversionRateA();
        return $views + $clicks + $rate;
    }

    /**
     * Get content B Overall Score
     *
     * @return int
     */
    public function getContentBOverall()
    {
        $views = $this->getContentBViews();
        $clicks = $this->getContentBClicks();
        $rate = $this->getConversionRateB();
        return $views + $clicks + $rate;
    }

    /**
     * Get both contents daily view count
     *
     * @return array
     */
    protected function _getDailyViews()
    {
        if (!$this->_dailyViews) {
            $id = $this->getRequest()->getParam('entity_id');
            $days = [];
            $lines = $this->statisticCollection->create()
                ->addFieldToFilter('test_id', $id)
                ->addFieldToFilter('action', 'view');
            $lines->getSelect()->order('created_at');
            foreach ($lines->getItems() as $line) {
                $date = date("d-m-Y", strtotime($line->getcreatedAt()));
                !isset($days[$date]) ? $days[$date] = [] : '';
                if ($line->getContent() == 1) {
                    isset($days[$date][1]) ? $days[$date][1] += 1 : $days[$date][1] = 1;
                } else {
                    isset($days[$date][2]) ? $days[$date][2] += 1 : $days[$date][2] = 1;
                }
            }
            $this->_dailyViews = $days;
        }
        return $this->_dailyViews;
    }

    /**
     * Get content A daily view count
     *
     * @return array
     */
    public function getContentADaily()
    {
        $aDaily = [];
        $dailyViews = $this->_getDailyViews();
        foreach ($dailyViews as $day) {
            $aDaily[] = $day[1] ?? 0;
        }
        return $aDaily;
    }

    /**
     * Get content B daily view count
     *
     * @return array
     */
    public function getContentBDaily()
    {
        $bDaily = [];
        $dailyViews = $this->_getDailyViews();
        foreach ($dailyViews as $day) {
            $bDaily[] = $day[2] ?? 0;
        }
        return $bDaily;
    }

    /**
     * Get daily labels for graph
     *
     * @return string
     */
    public function getDailyLabels()
    {
        $dailyViews = $this->_getDailyViews();
        $dates = array_keys($dailyViews);
        sort($dates);
        foreach ($dates as $k => $date) {
            $dates[$k] = sprintf("'%s'", $date);
        }
        return implode(',', $dates);
    }

    /**
     * Get content A unique checkouts
     *
     * @return int
     */
    protected function _getContentAUniqueCheckouts()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 1)
            ->addFieldToFilter('action', 'checkout');
        $collection->getSelect()->group('session_id');
        return $collection->count();
    }

    /**
     * Get content B unique checkouts
     *
     * @return int
     */
    protected function _getContentBUniqueCheckouts()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 2)
            ->addFieldToFilter('action', 'checkout');
        $collection->getSelect()->group('session_id');
        return $collection->count();
    }

    /**
     * Get content A conversion rate
     *
     * @return int
     */
    public function getConversionRateA()
    {
        $views = $this->getContentAUniqueViews();
        $checkouts = $this->_getContentAUniqueCheckouts();

        if (!$checkouts || !$views) {
            return 0;
        }

        return (($checkouts / $views) * 100);
    }

    /**
     * Get content B conversion rate
     *
     * @return int
     */
    public function getConversionRateB()
    {
        $views = $this->getContentBUniqueViews();
        $checkouts = $this->_getContentBUniqueCheckouts();

        if (!$checkouts || !$views) {
            return 0;
        }

        return (($checkouts / $views) * 100);
    }

    /**
     * Get content A average sales
     *
     * @return int
     */
    public function getAverageSaleA()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 1)
            ->addFieldToFilter('action', 'checkout');
        $n = $collection->count();
        $total = 0;
        foreach ($collection->getItems() as $checkout) {
            $total += $checkout->getSaleValue();
        }

        if (!$total || !$n) {
            return 0;
        }

        return ($total / $n);
    }

    /**
     * Get content B average sales
     *
     * @return int
     */
    public function getAverageSaleB()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $collection = $this->statisticCollection->create()
            ->addFieldToFilter('test_id', $id)
            ->addFieldToFilter('content', 2)
            ->addFieldToFilter('action', 'checkout');
        $n = $collection->count();
        $total = 0;
        foreach ($collection->getItems() as $checkout) {
            $total += $checkout->getSaleValue();
        }

        if (!$total || !$n) {
            return 0;
        }

        return ($total / $n);
    }

    /**
     * Get content A click percentage
     *
     * @return int
     */
    public function getClickAPercentage()
    {
        if (!$this->getContentAClicks()) {
            return 0;
        }

        return (($this->getContentAClicks() * 100) / ($this->getContentAClicks() + $this->getContentBClicks()));
    }

    /**
     * Get content B click percentage
     *
     * @return int
     */
    public function getClickBPercentage()
    {
        if (!$this->getContentBClicks()) {
            return 0;
        }

        return (($this->getContentBClicks() * 100) / ($this->getContentAClicks() + $this->getContentBClicks()));
    }

    /**
     * Get content A unique views percentage
     *
     * @return int
     */
    public function getUniqueAPercentage()
    {
        if (!$this->getContentAUniqueViews()) {
            return 0;
        }

        return (
            ($this->getContentAUniqueViews() * 100)
            / ($this->getContentAUniqueViews() + $this->getContentBUniqueViews())
        );
    }

    /**
     * Get content B unique views percentage
     *
     * @return int
     */
    public function getUniqueBPercentage()
    {
        if (!$this->getContentBUniqueViews()) {
            return 0;
        }

        return (
            ($this->getContentBUniqueViews() * 100)
            / ($this->getContentAUniqueViews() + $this->getContentBUniqueViews())
        );
    }

    /**
     * Get content A views percentage
     *
     * @return int
     */
    public function getViewAPercentage()
    {
        if (!$this->getContentAViews()) {
            return 0;
        }

        return (($this->getContentAViews() * 100) / ($this->getContentAViews() + $this->getContentBViews()));
    }

    /**
     * Get content B views percentage
     *
     * @return int
     */
    public function getViewBPercentage()
    {
        if (!$this->getContentBViews()) {
            return 0;
        }

        return (($this->getContentBViews() * 100) / ($this->getContentAViews() + $this->getContentBViews()));
    }

    /**
     * Get content A render count percentage
     *
     * @return int
     */
    public function getRenderAPercentage()
    {
        if (!$this->getContentARenderCount()) {
            return 0;
        }

        return (
            ($this->getContentARenderCount() * 100)
            / ($this->getContentARenderCount() + $this->getContentBRenderCount())
        );
    }

    /**
     * Get content B render count percentage
     *
     * @return int
     */
    public function getRenderBPercentage()
    {
        if (!$this->getContentBRenderCount()) {
            return 0;
        }

        return (
            ($this->getContentBRenderCount() * 100)
            / ($this->getContentARenderCount() + $this->getContentBRenderCount())
        );
    }
}
