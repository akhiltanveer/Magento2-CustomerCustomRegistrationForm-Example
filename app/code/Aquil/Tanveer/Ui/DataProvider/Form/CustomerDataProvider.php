<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/


namespace Aquil\Tanveer\Ui\DataProvider\Form;

use Aquil\Tanveer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;

class CustomerDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;
    protected $dataPersistor;
    protected $_request;
    protected $_storeManager;
    protected $customerCollectionFactory;
    private $fileSystem;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Filesystem $fileSystem,
        CollectionFactory $customerCollectionFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->customerCollectionFactory = $customerCollectionFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $customerCollectionFactory->create();
        $this->_request = $request;
        $this->_storeManager = $storeManager;
        $this->fileSystem  = $fileSystem;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $storeid = "";
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $itemId = $this->_request->getParam('id');
        $this->collection = $this->customerCollectionFactory->create();
        if (!empty($itemId)) {
            $items = $this->collection->getItems();
            foreach ($items as $item) {
              $imageData = $item->getData();
              $img = $imageData['profile_image'];
              $imageName = $imageData['name'];
              unset($imageData['profile_image']);
              $srcImage = $this->_storeManager->getStore()->getBaseUrl(
                              \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                          ) . 'custom-form/' . $img;
              $imageArr[0]['name'] = $imageName;
              $imageArr[0]['url'] = $srcImage;
              $imageArr[0]['file'] = $img;
              $imageData['profile_image'] = $imageArr;
              $this->loadedData[$item->getId()] = $imageData;
            }

            $data = $this->dataPersistor->get('customer');

            if (!empty($data)) {
                $page = $this->collection->getNewEmptyItem();
                $page->setData($data);
                $this->loadedData[$page->getId()] = $page->getData();
                $this->dataPersistor->clear('customer');
            }
        }

        return $this->loadedData;
    }
    /**
    * Get Destination Path
    *
    * @return $directory_list
    */
    public function getDestinationPath()
    {
      return $this->directory_list->getPath('media')."/Mageants/";
    }
}
