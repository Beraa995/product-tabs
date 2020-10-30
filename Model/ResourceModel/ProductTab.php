<?php
namespace BKozlic\ProductTabs\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductTab extends AbstractDb
{
    public function _construct()
    {
        $this->_init('bkozlic_product_tabs', 'entity_id');
    }
}
