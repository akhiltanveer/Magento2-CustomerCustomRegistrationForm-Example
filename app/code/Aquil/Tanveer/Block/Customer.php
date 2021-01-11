<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/

namespace Aquil\Tanveer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;

class Customer extends Template
{
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function getFormLoginAction()
    {
        return $this->getUrl('customform/customer/verify', ['_secure' => true]);
    }
    public function getFormRegisterAction()
    {
        return $this->getUrl('customform/customer/save', ['_secure' => true]);
    }
}
