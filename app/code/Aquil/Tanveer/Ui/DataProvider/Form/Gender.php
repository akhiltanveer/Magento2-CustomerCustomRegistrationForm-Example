<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/

namespace Aquil\Tanveer\Ui\DataProvider\Form;

class Gender implements \Magento\Framework\Option\ArrayInterface
{
  /**
   * @var \Magento\Store\Model\System\Store
   */
    protected $_systemStore;

  /**
   * @param \Magento\Store\Model\System\Store $systemStore
   */
    public function __construct(
        \Magento\Store\Model\System\Store $systemStore
    ) {
        $this->_systemStore = $systemStore;
    }

    public function toOptionArray()
    {
        $male = __('Male');
        $female = __('Female');
        $options = [
             0 => [
                 'label' => $male,
                 'value' => $male
             ],
             1 => [
                 'label' => $female,
                 'value' => $female
             ],
         ];

         return $options;
    }
}
