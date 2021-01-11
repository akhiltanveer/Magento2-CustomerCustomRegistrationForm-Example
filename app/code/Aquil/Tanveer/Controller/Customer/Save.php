<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Aquil\Tanveer\Controller\Customer;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Message\Manager;



class Save extends Action
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
            $resultRedirect->setPath('customform/customer/registration');
              return $resultRedirect;
        }


        try {
          $checkExistingUser = $this->checkExistingUser($post['email']);
          if($checkExistingUser >= 1) {
                $this->messageManager->getMessages(true);
                $this->messageManager->addErrorMessage(
                    __('an account with this email already exists')
                    );
                $resultRedirect = $this->redirectFactory->create();
                $resultRedirect->setPath('customform/customer/registration');
                  return $resultRedirect;
          }
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $filesData = $this->getRequest()->getFiles('profile_image');

            if ($filesData['name']) {
                try {
                    $uploader = $this->fileUploaderFactory->create(['fileId' => 'profile_image']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $uploader->setAllowCreateFolders(true);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx']);
                    $path = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('custom-form');
                    $result = $uploader->save($path);

                    $upload_document = 'contact-doc'.$uploader->getUploadedFilename();
                    $filePath = $result['path'].$result['file'];
                    $path = explode("custom-form",$filePath);
                    $fileName = $path[1];
                    $model = $this->customCustomer;
                    $post['profile_image'] = $fileName;
                    $model->setData($post);
                    $model->save();
                        $this->messageManager->getMessages(true);
                        $sessionMessage = __('Account Successfully Created');
                        $this->messageManager->addSuccessMessage($sessionMessage);
                        $resultRedirect = $this->redirectFactory->create();
                        $resultRedirect->setPath('customform/customer/registration');
                          return $resultRedirect;

                }catch (\Exception $e) {
                  $this->messageManager->addErrorMessage(
                      __('please fill in again')
                  );
                  $resultRedirect = $this->redirectFactory->create();
                  $resultRedirect->setPath('customform/customer/registration');
                        return $resultRedirect;
                }

            } else {
                $upload_document = '';
                $filePath = '';
                $fileName = '';
            }
            if ($error) {
                throw new \Exception();
            }


        } catch (\Exception $e) {
          $this->messageManager->addErrorMessage(
              __('please fill in again')
          );
          $resultRedirect = $this->redirectFactory->create();
          $resultRedirect->setPath('customform/customer/registration');
                return $resultRedirect;
        }
    }
    public function checkExistingUser($email) {
      $collection = $this->customCustomer->getCollection()
                    ->addFieldToFilter('email', $email);
        $storeArray = $collection->getData();
        $limitCount = count($storeArray);
        return $limitCount;
    }


}
