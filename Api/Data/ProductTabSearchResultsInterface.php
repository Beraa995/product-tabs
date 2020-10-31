<?php
namespace BKozlic\ProductTabs\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface ProductTabSearchResultsInterface
 */
interface ProductTabSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get tab list.
     *
     * @return ProductTabInterface[]
     */
    public function getItems();

    /**
     * Set tab list.
     *
     * @param ProductTabInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
