<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/
namespace Aquil\Tanveer\Controller\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Result\Page;

class Myaccount extends \Magento\Framework\App\Action\Action
{
    /**
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;


    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        return parent::__construct($context);
    }
    /**
     * @return \Magento\Framework\View\Result\PageFactory
     */

    public function execute()
    {
      $resultPage = $this->pageFactory->create();
      return $resultPage;
    }
}
