<?php

class MercadoPago_OneStepCheckout_Block_Custom_Form
    extends MercadoPago_Core_Block_Custom_Form
{

    protected function _prepareLayout()
    {

        $public_key = Mage::getStoreConfig(MercadoPago_Core_Helper_Data::XML_PATH_PUBLIC_KEY);

        //init js no header
        $block = Mage::app()->getLayout()->createBlock('core/text', 'js_mercadopago');
        if (Mage::getStoreConfigFlag(MercadoPago_OneStepCheckout_Helper_Data::XML_PATH_ONS_ACTIVE)) {
            $block->setText(
                sprintf(
                    '
                    <script type="text/javascript">var PublicKeyMercadoPagoCustom = "' . $public_key . '";</script>
                    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
                    <script type="text/javascript" src="%s"></script>',
                    Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS, true) . 'mercadopago/mercadopago_osc.js'
                )
            );
        } else {
            $block->setText(
                sprintf(
                    '
                    <script type="text/javascript">var PublicKeyMercadoPagoCustom = "' . $public_key . '";</script>
                    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
                    <script type="text/javascript" src="%s"></script>',
                    Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS, true) . 'mercadopago/mercadopago.js'
                )
            );
        }

        $head = Mage::app()->getLayout()->getBlock('after_body_start');

        if ($head) {
            $head->append($block);
        }

        return Mage_Payment_Block_Form_Cc::_prepareLayout();
    }
}