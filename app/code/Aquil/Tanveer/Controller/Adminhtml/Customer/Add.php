<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/

namespace Aquil\Tanveer\Controller\Adminhtml\Customer;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class Add extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;


    /**
     * AbstractAction constructor.
     *
     * @param Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getId();
        if ($id) {
            try {
                $model = $this->_objectManager
                ->create(\Aquil\Tanveer\Model\Customer::class)->load($id);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('*/*/*');
                return;
            }
        } else {
                $model = $this->_objectManager->create(\Aquil\Tanveer\Model\Customer::class);
        }
        // set entered data if was error when we do save
        $data = $this->_objectManager->get(\Magento\Backend\Model\Session::class)->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);

        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Aquil_Tanveer::customer');
        $resultPage->getConfig()->getTitle()->prepend(__('Add Customer'));
        $resultPage->addBreadcrumb(__('Customer'), __('Customer'));
        $resultPage->addBreadcrumb(__('Manage Customers'), __('Manage Customers'));
        return $resultPage;
    }

    protected function getId()
    {
        return $this->getRequest()->getParam('id');
    }
}
