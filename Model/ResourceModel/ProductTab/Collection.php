<?php
namespace BKozlic\ProductTabs\Model\ResourceModel\ProductTab;

use BKozlic\ProductTabs\Model\ProductTab as ProductTabModel;
use BKozlic\ProductTabs\Model\ResourceModel\ProductTab as ProductTabResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_eventPrefix = 'bkozlic_product_tabs_collection';

    protected $_eventObject = 'bkozlic_product_tabs_collection';

    public function _construct()
    {
        $this->_init(ProductTabModel::class, ProductTabResource::class);
    }
}
