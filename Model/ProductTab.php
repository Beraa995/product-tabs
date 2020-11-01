<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Model;

use BKozlic\ProductTabs\Api\Data\ProductTabExtensionInterface;
use BKozlic\ProductTabs\Api\Data\ProductTabInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class ProductTab extends AbstractExtensibleModel implements ProductTabInterface, IdentityInterface
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

    /**
     * @return ProductTabExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     *
     * @param ProductTabExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(ProductTabExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Returns tab id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::TAB_ID);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return ProductTabInterface
     */
    public function setId($id)
    {
        return $this->setData(self::TAB_ID, $id);
    }

    /**
     * Returns tab class
     *
     * @return string|null
     */
    public function getClass()
    {
        return $this->getData(self::TAB_CLASS);
    }

    /**
     * Set tab class
     *
     * @param string $class
     * @return ProductTabInterface
     */
    public function setClass($class)
    {
        return $this->setData(self::TAB_CLASS, $class);
    }

    /**
     * Returns tab content
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Set tab content
     *
     * @param string $content
     * @return ProductTabInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Returns is active tab
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set tab is active value
     *
     * @param bool $isActive
     * @return ProductTabInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Returns tab sort number
     *
     * @return int
     */
    public function getTabSort()
    {
        return $this->getData(self::TAB_SORT);
    }

    /**
     * Set tab sort number
     *
     * @param int $order
     * @return ProductTabInterface
     */
    public function setTabSort($order)
    {
        return $this->setData(self::TAB_SORT, $order);
    }

    /**
     * Returns tab title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Set tab title
     *
     * @param string $title
     * @return ProductTabInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Returns tab creation time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Set tab creation time
     *
     * @param string $creationTime
     * @return ProductTabInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::CREATION_TIME, $creationTime);
    }

    /**
     * Returns tab update time
     *
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Set tab update time
     *
     * @param string $updateTime
     * @return ProductTabInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }
}
