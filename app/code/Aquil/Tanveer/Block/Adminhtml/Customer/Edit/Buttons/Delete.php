<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/

namespace Aquil\Tanveer\Block\Adminhtml\Customer\Edit\Buttons;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Context as UiContext;

class Delete extends \Magento\Backend\Block\Template implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    protected $uiContext;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        UiContext $uiContext
    ) {
        $this->context = $context;
        $this->uiContext = $uiContext;
    }

    /**
     * get button data
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($categoryId = $this->context->getRequest()->getParam('id')) {
            $url = $this->uiContext->getUrl('*/*/delete', ['id'=>$categoryId]);
            $data = [
                'label' => __('Delete'),
                'class' => 'delete',
                'on_click' => sprintf("deleteConfirm(
                    'Are you sure you want to delete this Customer?',
                    '%s'
                )", $url),
                'sort_order' => 20,
            ];
        }
        return $data;
    }
}
