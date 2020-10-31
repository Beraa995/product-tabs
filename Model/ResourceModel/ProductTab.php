<?php
namespace BKozlic\ProductTabs\Model\ResourceModel;

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
}
