<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Observer;

use BKozlic\ProductTabs\Helper\Data;
use BKozlic\ProductTabs\Model\ProductTab;
use BKozlic\ProductTabs\Model\ResourceModel\ProductTab\CollectionFactory as TabFactory;
use Exception;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Layout;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class AddRemoveTabObserver
 */
class AddRemoveTabObserver implements ObserverInterface
{
    /**
     * @var TabFactory
     */
    protected $tabCollection;

    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param TabFactory $tabCollection ,
     * @param FilterProvider $filterProvider
     * @param StoreManagerInterface $storeManager
     * @param Data $helper
     */
    public function __construct(
        TabFactory $tabCollection,
        FilterProvider $filterProvider,
        StoreManagerInterface $storeManager,
        Data $helper
    ) {
        $this->tabCollection = $tabCollection;
        $this->filterProvider = $filterProvider;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
    }

    /**
     * Remove blocks depending on backend configuration
     *
     * @param $observer
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /** @var Layout $layout */
        $layout = $observer->getLayout();
        $handle = $layout->getUpdate()->getHandles();

        if ($this->helper->isEnabled() && in_array('catalog_product_view', $handle)) {
            $this->customizeDefaultTabs($layout);
            $this->addTabs($layout);
        }
    }

    /**
     * Customize default tabs on product page
     *
     * @param $layout
     */
    protected function customizeDefaultTabs($layout)
    {
        $details = $layout->getBlock('product.info.description');
        $review = $layout->getBlock('reviews.tab');
        $moreInfo = $layout->getBlock('product.attributes');

        if ($details) {
            if ($this->helper->hideDetails()) {
                $layout->unsetElement('product.info.description');
            } else {
                $details->setSortOrder($this->helper->getDetailsTabSortNumber());
            }
        }

        if ($review) {
            if ($this->helper->hideReviews()) {
                $layout->unsetElement('reviews.tab');
            } else {
                $review->setSortOrder($this->helper->getReviewsTabSortNumber());
            }
        }

        if ($moreInfo) {
            if ($this->helper->hideMoreInfo()) {
                $layout->unsetElement('product.attributes');
            } else {
                $moreInfo->setSortOrder($this->helper->getMoreInfoTabSortNumber());
            }
        }
    }

    /**
     * Add tabs to layout
     *
     * @param $layout
     * @throws NoSuchEntityException
     * @throws Exception
     */
    protected function addTabs($layout)
    {
        $tabWrapper = $layout->getBlock('product.info.details');
        $storeId = $this->storeManager->getStore()->getId();

        if ($tabWrapper) {
            $tabCollection = $this->tabCollection->create()
                ->addFieldToFilter('is_active', ['eq' => 1])
                ->addFieldToFilter('store_id', $storeId);

            /** @var ProductTab $tab */
            foreach ($tabCollection as $tab) {
                $layout->addBlock(Template::class, 'product-tab' . $tab->getId(), 'product.info.details');
                $layout->getBlock('product-tab' . $tab->getId())
                    ->setTemplate('BKozlic_ProductTabs::renderer.phtml')
                    ->setTitle($tab->getTitle())
                    ->setClass($tab->getClass())
                    ->setSortOrder($tab->getTabSort())
                    ->setContent($this->filterProvider->getBlockFilter()->filter($tab->getContent()));
                $layout->addToParentGroup('product-tab' . $tab->getId(), 'detailed_info');
            }
        }
    }
}
