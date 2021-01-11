<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/
namespace Aquil\Tanveer\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class CustomFormWidget extends Template implements BlockInterface {

	protected $_template = "widget/customformwidget.phtml";
	public function getFormAction()
	{
			return $this->getUrl('customform/CustomContactUsingBlock/CustomContactUsingBlockSubmit', ['_secure' => true]);
	}
}
