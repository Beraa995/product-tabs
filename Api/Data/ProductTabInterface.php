<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Api\Data;

use BKozlic\ProductTabs\Api\Data\ProductTabExtensionInterface;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface ProductTabInterface
 */
interface ProductTabInterface extends ExtensibleDataInterface
{
    const TAB_ID = 'tab_id';
    const IS_ACTIVE = 'is_active';
    const TAB_SORT = 'tab_sort';
    const TITLE = 'title';
    const TAB_CLASS = 'tab_class';
    const CONTENT = 'content';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME = 'update_time';

    /**
     * Returns tab id
     *
     * @return int
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return ProductTabInterface
     */
    public function setId($id);

    /**
     * Returns tab class
     *
     * @return string|null
     */
    public function getClass();

    /**
     * Set class
     *
     * @param string $class
     * @return ProductTabInterface
     */
    public function setClass($class);

    /**
     * Returns content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Set content
     *
     * @param string $content
     * @return ProductTabInterface
     */
    public function setContent($content);

    /**
     * Returns is active data
     *
     * @return bool
     */
    public function getIsActive();

    /**
     * Set is active
     *
     * @param bool $isActive
     * @return ProductTabInterface
     */
    public function setIsActive($isActive);

    /**
     * Returns tab sort number
     *
     * @return int
     */
    public function getTabSort();

    /**
     * Set tab order
     *
     * @param int $order
     * @return ProductTabInterface
     */
    public function setTabSort($order);

    /**
     * Returns title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set title
     *
     * @param string $title
     * @return ProductTabInterface
     */
    public function setTitle($title);

    /**
     * Returns tab creation time
     *
     * @return string
     */
    public function getCreationTime();

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return ProductTabInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Returns tab update time
     *
     * @return string
     */
    public function getUpdateTime();

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return ProductTabInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return ProductTabExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param ProductTabExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(ProductTabExtensionInterface $extensionAttributes);
}
