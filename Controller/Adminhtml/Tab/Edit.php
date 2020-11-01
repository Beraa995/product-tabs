<?php
/**
 * @category  BKozlic
 * @package   BKozlic\ProductTabs
 * @author    Berin Kozlic - berin.kozlic@gmail.com
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace BKozlic\ProductTabs\Controller\Adminhtml\Tab;

use BKozlic\ProductTabs\Model\ProductTabFactory;
use BKozlic\ProductTabs\Model\ProductTabRepository;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization
     */
    const ADMIN_RESOURCE = 'BKozlic_ProductTabs::producttabs';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var ProductTabRepository
     */
    protected $tabRepository;

    /**
     * @var ProductTabFactory
     */
    protected $tabFactory;

    /**
     * @var Redirect
     */
    protected $resultRedirectFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param ProductTabRepository $tabRepository
     * @param ProductTabFactory $tabFactory
     * @param Redirect $resultRedirectFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        ProductTabRepository $tabRepository,
        ProductTabFactory $tabFactory,
        Redirect $resultRedirectFactory,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->tabRepository = $tabRepository;
        $this->tabFactory = $tabFactory;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Edit action
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('tab_id');
        $tabModel = $this->tabFactory->create();

        if ($id) {
            try {
                $tabModel = $this->tabRepository->get($id);
            } catch (NoSuchEntityException $exception) {
            } catch (LocalizedException $e) {
            }

            if (!$tabModel->getId()) {
                $this->messageManager->addErrorMessage(__('This tab no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->dataPersistor->set('producttab', $tabModel);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Product Tabs'));
        $resultPage->getConfig()->getTitle()->prepend(
            $tabModel->getId() ? $tabModel->getTitle() : __('New Product Tab')
        );
        return $resultPage;
    }
}
