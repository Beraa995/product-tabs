<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * Helper constants
     */
    const XML_PATH_ENABLED = 'product_tab_general/general/enabled';
    const XML_PATH_HIDE_DETAILS = 'product_tab_general/default_tabs/details';
    const XML_PATH_HIDE_DETAILS_SORT = 'product_tab_general/default_tabs_sort/details_sort';
    const XML_PATH_HIDE_MORE = 'product_tab_general/default_tabs/more';
    const XML_PATH_HIDE_MORE_SORT = 'product_tab_general/default_tabs_sort/more_sort';
    const XML_PATH_HIDE_REVIEWS = 'product_tab_general/default_tabs/review';
    const XML_PATH_HIDE_REVIEWS_SORT = 'product_tab_general/default_tabs_sort/review_sort';

    /**
     * Checks if extension is enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Checks if details tab should be hidden
     *
     * @return boolean
     */
    public function hideDetails()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_HIDE_DETAILS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns details tab sort number
     *
     * @return string
     */
    public function getDetailsTabSortNumber()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_HIDE_DETAILS_SORT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Checks if more information tab should be hidden
     *
     * @return boolean
     */
    public function hideMoreInfo()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_HIDE_MORE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns more information tab sort number
     *
     * @return string
     */
    public function getMoreInfoTabSortNumber()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_HIDE_MORE_SORT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Checks if reviews tab should be hidden
     *
     * @return boolean
     */
    public function hideReviews()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_HIDE_REVIEWS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Returns reviews tab sort number
     *
     * @return string
     */
    public function getReviewsTabSortNumber()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_HIDE_REVIEWS_SORT, ScopeInterface::SCOPE_STORE);
    }
}
