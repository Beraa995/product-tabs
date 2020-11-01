<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Model\ResourceModel;

use BKozlic\ProductTabs\Ui\Component\Listing\Column\StoreOptions;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductTab extends AbstractDb
{
    /**
     * Main table primary key field name
     *
     * @var string
     */
    protected $_idFieldName = 'tab_id';

    public function _construct()
    {
        $this->_init('bkozlic_product_tabs', 'tab_id');
    }

    /**
     * Perform operations after collection save
     *
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterSave($object)
    {
        $connection = $this->getConnection();
        $storeTable = $this->getTable('bkozlic_product_tabs_store');
        $stores = $object->getData('store_id');

        if (!$stores) {
            $stores = [StoreOptions::ALL_STORE_VIEWS];
        }

        // Delete records for tab in store table
        $where = [
            $this->_idFieldName . ' = ?' => (int)$object->getData($this->_idFieldName)
        ];
        $connection->delete($storeTable, $where);

        $data = [];
        foreach ($stores as $storeId) {
            $data[] = [
                $this->_idFieldName => (int)$object->getData($this->_idFieldName),
                'store_id' => (int)$storeId,
            ];
        }
        $connection->insertMultiple($storeTable, $data);
        return $this;
    }
}
