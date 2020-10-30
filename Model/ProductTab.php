<?php
namespace BKozlic\ProductTabs\Model;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class ProductTab extends AbstractExtensibleModel implements IdentityInterface, ExtensibleDataInterface
{
    const CACHE_TAG = 'bkozlic_product_tabs';

    protected $_cacheTag = 'bkozlic_product_tabs';

    protected $_eventPrefix = 'bkozlic_product_tabs';

    public function _construct()
    {
        $this->_init(ResourceModel\ProductTab::class);
    }

    /**
     * @inheridoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
