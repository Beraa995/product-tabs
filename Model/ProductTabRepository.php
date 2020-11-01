<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Model;

use BKozlic\ProductTabs\Api\Data\ProductTabSearchResultsInterfaceFactory;
use BKozlic\ProductTabs\Api\Data\ProductTabSearchResultsInterface;
use BKozlic\ProductTabs\Api\Data\ProductTabInterface;
use BKozlic\ProductTabs\Api\ProductTabRepositoryInterface;
use BKozlic\ProductTabs\Model\ResourceModel\ProductTab as ProductTabResource;
use BKozlic\ProductTabs\Model\ResourceModel\ProductTab\CollectionFactory as ProductTabCollection;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ProductTabRepository
 */
class ProductTabRepository implements ProductTabRepositoryInterface
{
    /**
     * @var ProductTabResource
     */
    protected $resource;

    /**
     * @var ProductTabFactory
     */
    protected $tabFactory;

    /**
     * @var ProductTabCollection
     */
    protected $collectionFactory;

    /**
     * @var ProductTabSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * ProductTabRepository constructor.
     * @param ProductTabSearchResultsInterfaceFactory $searchResultsFactory
     * @param ProductTabFactory $tabFactory
     * @param ProductTabCollection $collectionFactory
     * @param ProductTabResource $resource
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ProductTabSearchResultsInterfaceFactory $searchResultsFactory,
        ProductTabFactory $tabFactory,
        ProductTabCollection $collectionFactory,
        ProductTabResource $resource,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->tabFactory = $tabFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->storeManager = $storeManager;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
    }

    /**
     * Save tab data
     *
     * @param ProductTabInterface $tab
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @return ProductTab
     */
    public function save(ProductTabInterface $tab)
    {
        if (empty($tab->getStoreId())) {
            $tab->setStoreId($this->storeManager->getStore()->getId());
        }

        try {
            $this->resource->save($tab);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $tab;
    }

    /**
     * Load tab by id
     *
     * @param string $tabId
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @return ProductTab
     */
    public function get($tabId)
    {
        $tab = $this->tabFactory->create();
        $this->resource->load($tab, $tabId);

        if (!$tab->getId()) {
            throw new NoSuchEntityException(__('The tab with the "%1" ID doesn\'t exist.', $tabId));
        }

        return $tab;
    }

    /**
     * Load tab data collection by given search criteria
     *
     * @param SearchCriteriaInterface $criteria
     * @return ProductTabSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $collection = $this->collectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection);
        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * Delete tab
     *
     * @param ProductTabInterface $tab
     * @throws CouldNotDeleteException
     * @return bool
     */
    public function delete(ProductTabInterface $tab)
    {
        try {
            $this->resource->delete($tab);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete tab by ID
     *
     * @param string $tabId
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @return bool
     */
    public function deleteById($tabId)
    {
        return $this->delete($this->get($tabId));
    }
}
