<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Controller\Adminhtml\Tab;

use BKozlic\ProductTabs\Model\ProductTabFactory;
use BKozlic\ProductTabs\Model\ResourceModel\ProductTab as ProductTabResource;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Tab's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * Authorization
     */
    const ADMIN_RESOURCE = 'BKozlic_ProductTabs::producttabs';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ProductTabFactory
     */
    protected $tabFactory;

    /**
     * @var ProductTabResource
     */
    protected $tabResource;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ProductTabFactory $tabFactory
     * @param ProductTabResource $tabResource
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        ProductTabFactory $tabFactory,
        ProductTabResource $tabResource
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->tabFactory = $tabFactory;
        $this->tabResource = $tabResource;
    }

    /**
     * Save action
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = self::STATUS_ENABLED;
            }

            if (empty($data['tab_id'])) {
                $data['tab_id'] = null;
            }

            $productTabModel = $this->tabFactory->create();

            $id = $this->getRequest()->getParam('tab_id');
            if ($id) {
                try {
                    $this->tabResource->load($productTabModel, $id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This tab no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $productTabModel->setData($data);

            try {
                $this->tabResource->save($productTabModel);
                $this->messageManager->addSuccessMessage(__('You saved the tab.'));
                $this->dataPersistor->clear('producttab');
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the tab.'));
            }

            $this->dataPersistor->set('producttab', $data);

            return $resultRedirect->setPath('*/*/edit', ['tab_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
