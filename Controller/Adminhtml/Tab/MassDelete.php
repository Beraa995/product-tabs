<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Controller\Adminhtml\Tab;

use BKozlic\ProductTabs\Model\ResourceModel\ProductTab\CollectionFactory;
use BKozlic\ProductTabs\Model\ResourceModel\ProductTab as ProductTabResource;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization
     */
    const ADMIN_RESOURCE = 'BKozlic_ProductTabs::producttabs';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ProductTabResource
     */
    protected $productTabResource;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ProductTabResource $productTabResource
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ProductTabResource $productTabResource
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productTabResource = $productTabResource;
    }

    /**
     * Execute action
     *
     * @throws LocalizedException|\Exception
     * @return Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $tab) {
            $this->productTabResource->delete($tab);
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
