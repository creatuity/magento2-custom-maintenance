<?php

namespace Creatuity\CustomMaintenance\Block\Adminhtml\System\Config\Form\Preview;

class Button extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $url = $this->getUrl('adminhtml/preview/preview', [
            'store_id' => $this->getRequest()->getParam('store'),
        ]);
        
        $button = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
            ->setData([
                'id' => 'preview_button',
                'label' => __('Preview'),
                'onclick' => "window.open('$url');"
            ]);
        
        return $button->toHtml();
    }
}
