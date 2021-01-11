<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/
namespace Aquil\Tanveer\Model\ResourceModel\Customer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
       // @codingStandardsIgnoreStart
          $this->_init('Aquil\Tanveer\Model\Customer',
          'Aquil\Tanveer\Model\ResourceModel\Customer');
       // @codingStandardsIgnoreEnd
    }
}
