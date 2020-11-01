<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Controller\Adminhtml\Tab;

use BKozlic\ProductTabs\Model\ProductTabFactory;
use BKozlic\ProductTabs\Model\ResourceModel\ProductTab;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Delete
 */
class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization
     */
    const ADMIN_RESOURCE = 'BKozlic_ProductTabs::producttabs';

    /**
     * @var ProductTab
     */
    protected $tabResource;

    /**
     * @var ProductTabFactory
     */
    protected $productTabFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param ProductTab $tabResource
     * @param ProductTabFactory $productTabFactory
     */
    public function __construct(
        Action\Context $context,
        ProductTab $tabResource,
        ProductTabFactory $productTabFactory
    ) {
        parent::__construct($context);
        $this->tabResource = $tabResource;
        $this->productTabFactory = $productTabFactory;
    }

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $emptyProductTabObject = $this->productTabFactory->create();

        $id = $this->getRequest()->getParam('tab_id');
        if ($id) {
            try {
                $this->tabResource->load($emptyProductTabObject, $id);
                $this->tabResource->delete($emptyProductTabObject);
                $this->messageManager->addSuccessMessage(__('You have successfully deleted the tab.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['tab_id' => $id]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a tab to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
