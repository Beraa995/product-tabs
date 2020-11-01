<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Model\ResourceModel\ProductTab;

use BKozlic\ProductTabs\Model\ProductTab as ProductTabModel;
use BKozlic\ProductTabs\Model\ResourceModel\ProductTab as ProductTabResource;
use Exception;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Collection extends AbstractCollection
{
    protected $_eventPrefix = 'bkozlic_product_tabs_collection';

    protected $_eventObject = 'bkozlic_product_tabs_collection';

    protected $_idFieldName = 'tab_id';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Product Tab Collection constructor.
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param StoreManagerInterface $storeManager
     * @param AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->storeManager = $storeManager;
    }

    public function _construct()
    {
        $this->_init(ProductTabModel::class, ProductTabResource::class);
        $this->_map['fields']['store'] = 'bkozlic_product_tabs_store.store_id';
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from('bkozlic_product_tabs_store');
        $result = $connection->fetchAll($select);

        if ($result) {
            $storesData = [];
            foreach ($result as $storeData) {
                $storesData[$storeData[$this->_idFieldName]][] = $storeData['store_id'];
            }

            foreach ($this as $item) {
                $linkedId = $item->getData($this->_idFieldName);
                if (!isset($storesData[$linkedId])) {
                    continue;
                }

                $item->setData('store_id', $storesData[$linkedId]);
            }
        }

        return parent::_afterLoad();
    }

    /**
     * Add filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Store) {
            $store = [$store->getId()];
        }

        if (!is_array($store)) {
            $store = [$store];
        }

        if ($withAdmin) {
            $store[] = Store::DEFAULT_STORE_ID;
        }

        $this->addFilter('store', ['in' => $store], 'public');

        return $this;
    }

    /**
     * Add field filter to collection
     *
     * @param array|string $field
     * @param string|int|array|null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, true);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Join store relation table if there is store filter
     *
     * @throws Exception
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                'bkozlic_product_tabs_store',
                'main_table.' . $this->_idFieldName . ' = bkozlic_product_tabs_store.' . $this->_idFieldName,
                []
            )->group(
                'main_table.' . $this->_idFieldName
            );
        }

        parent::_renderFiltersBefore();
    }
}
