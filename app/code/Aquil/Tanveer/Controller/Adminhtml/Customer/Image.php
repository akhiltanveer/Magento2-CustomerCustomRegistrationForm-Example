<?php
namespace Aquil\Tanveer\Controller\Adminhtml\Customer;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class Image extends \Magento\Backend\App\Action
{
	/**
	 * @var string
	 */
	const IMAGE_TMP_PATH    = 'aquiltanveer/tmp/images/image';
	protected $imageUploader;
	private $fileUploaderFactory;
	private $fileSystem;
	protected $_storeManager;
	protected $baseTmpPath;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		Filesystem $fileSystem,
  	$baseTmpPath = self::IMAGE_TMP_PATH,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
		\Magento\Catalog\Model\ImageUploader $imageUploader
	) {
		parent::__construct($context);
    $this->fileUploaderFactory = $fileUploaderFactory;
		$this->imageUploader = $imageUploader;
		$this->_storeManager = $storeManager;
    $this->baseTmpPath = $baseTmpPath;
		$this->fileSystem          = $fileSystem;
	}

	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Aquil_Tanveer::customer');
	}

	public function execute()
	{
		try {

			$uploader = $this->fileUploaderFactory->create(['fileId' => 'profile_image']);
			$uploader->setAllowRenameFiles(true);
			$uploader->setFilesDispersion(true);
			$uploader->setAllowCreateFolders(true);
			$uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx']);
			$path = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('custom-form');

			$result = $uploader->save($path);
			$result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
			$result['path'] = str_replace('\\', '/', $result['path']);

			$result['url'] = $this->_storeManager->getStore()->getBaseUrl(
										\Magento\Framework\UrlInterface::URL_TYPE_MEDIA
								) . 'custom-form' . $result['file'];


		} catch (\Exception $e) {

			$result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
		}
		return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
	}
	/**
	 * Retrieve path
	 *
	 * @param string $path
	 * @param string $name
	 *
	 * @return string
	 */
	public function getFilePath($path, $name)
	{
			return rtrim($path, '/') . '/' . ltrim($name, '/');
	}
	/**
		* Set base tmp path
		*
		* @param string $baseTmpPath
		*
		* @return void
		*/
	 public function setBaseTmpPath($baseTmpPath)
	 {
			 $this->baseTmpPath = $baseTmpPath;
	 }

}
