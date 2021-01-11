<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/
namespace Aquil\Tanveer\Controller\Customer;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Message\Manager;



class Verify extends Action
{
  /**
   * @var \Magento\Store\Model\StoreManagerInterface
   */
    protected $storeManager;
    protected $context;
    private $fileUploaderFactory;
    private $fileSystem;
    protected $customCustomer;
    protected $redirectFactory;
    protected $messageManager;

     public function __construct(
        \Magento\Framework\App\Action\Context $context,
        Filesystem $fileSystem,
        Manager $messageManager,
        \Aquil\Tanveer\Model\Customer $customCustomer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->messageManager = $messageManager;
        $this->redirectFactory = $redirectFactory;
        $this->fileSystem          = $fileSystem;
        $this->customCustomer = $customCustomer;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Post user question
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        if (!$this->getRequest()->isPost()) {
            $this->messageManager->getMessages(true);
            $sessionMessage = __('session expire');
            $this->messageManager->addErrorMessage($sessionMessage);
            $resultRedirect = $this->redirectFactory->create();
            $resultRedirect->setPath('customform/customer/login');
              return $resultRedirect;
        }

        try {
          $validateUser = $this->validateUser($post['email'],$post['password']);
          $limitCount = count($validateUser);
          if($limitCount >= 1) {
                $this->messageManager->getMessages(true);
                $sessionMessage = __('logged in Successfully');
                $this->messageManager->addSuccessMessage($sessionMessage);
                $resultRedirect = $this->redirectFactory->create();
                $argument = ['name' => $validateUser[0]['name'],'contact'=>$validateUser[0]['contact'], '_current' => true];
                $resultRedirect->setPath('customform/customer/myaccount',$argument);
                return $resultRedirect;
          }
          else {
            $this->messageManager->addErrorMessage(
                __('incorrect email or password please try again')
            );
            $resultRedirect = $this->redirectFactory->create();
            $resultRedirect->setPath('customform/customer/login');
                  return $resultRedirect;
          }


        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('please fill in again')
            );
            $resultRedirect = $this->redirectFactory->create();
            $resultRedirect->setPath('customform/customer/login');
                  return $resultRedirect;
        }
    }
    public function validateUser($email,$password) {
      $collection = $this->customCustomer->getCollection()
                    ->addFieldToFilter('email', $email)
                    ->addFieldToFilter('password', $password);
        $storeArray = $collection->getData();
        return $storeArray;
    }


}
