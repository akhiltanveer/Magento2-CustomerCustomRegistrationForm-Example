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
use Psr\Log\LoggerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Locale\ResolverInterface;

class Save extends Action
{

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    protected $customCustomer;

    protected $storeRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Aquil\Tanveer\Model\Customer $customCustomer,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultLayoutFactory = $resultLayoutFactory;
        $this->storeRepository= $storeRepository;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customCustomer = $customCustomer;
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
      if ($this->getRequest()->getPostValue()) {

        $model = $this->customCustomer;
        $data = $this->getRequest()->getPostValue();

            try {
                $data = $this->getRequest()->getPostValue();
                $idPosted = $data['id'];
                $checkExistingUser = $this->checkExistingUser($data['email']);
                $limitCount = count($checkExistingUser);
                if($limitCount >= 1) {
                  if($checkExistingUser[0]['id'] != $idPosted) {
                  $this->messageManager->addError(
                      __('an account already exists.')
                  );
                  $this->_redirect('*/*/index', ['id' => $this->getRequest()->getParam('id')]);
                  return;

                  }
                }
                if(isset($data['profile_image'])) {
                  if(isset($data['profile_image'][0]['file'])) {
                    $imgPath = $data['profile_image'][0]['file'];
                    unset($data['profile_image']);
                    $data['profile_image'] = $imgPath;
                  }
                }
                if (isset($data['id']) && $id = $data['id']) {
                    $model = $model->load($id);
                } else {
                    unset($data['id']);
                }
                $model->setData($data);
                $model->save();

                $this->messageManager->addSuccess(__('Customer has been saved.'));
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the Customer data. Please try again.')
                );
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }

            $this->_redirect('customer/customer/index');
        }
        else {
          $this->messageManager->addError(
              __('Something went wrong while saving the Customer data. Please try again.')
          );
          $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
          return;
        }
    }
    public function checkExistingUser($email) {
      $collection = $this->customCustomer->getCollection()
                    ->addFieldToFilter('email', $email);
        $storeArray = $collection->getData();
        return $storeArray;
    }

}
