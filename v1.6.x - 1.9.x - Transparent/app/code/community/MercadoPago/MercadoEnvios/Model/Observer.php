<?php

class MercadoPago_MercadoEnvios_Model_Observer
{

    protected $_useMercadoEnvios;

    public function filterActivePaymentMethods($observer)
    {
        if ($this->_useMercadoEnvios()) {
            $event = $observer->getEvent();
            $method = $event->getMethodInstance();
            $result = $event->getResult();
            if ($method->getCode() != 'mercadopago_standard') {
                $result->isAvailable = false;
            }
        }
    }

    protected function _useMercadoEnvios()
    {
        if (empty($this->_useMercadoEnvios)) {
            $quote = Mage::helper('mercadopago_mercadoenvios')->getQuote();
            $shippingMethod = $quote->getShippingAddress()->getShippingMethod();
            $this->_useMercadoEnvios = Mage::helper('mercadopago_mercadoenvios')->isMercadoEnviosMethod($shippingMethod);
        }

        return $this->_useMercadoEnvios;
    }

    public function trackingPopup($observer)
    {
        $shippingInfoModel = Mage::getModel('shipping/info')->loadByHash(Mage::app()->getRequest()->getParam('hash'));

        if ($url = Mage::helper('mercadopago_mercadoenvios')->getTrackingUrlByShippingInfo($shippingInfoModel)) {
            Mage::app()->getRequest()->setDispatched(true);
            Mage::app()->getResponse()->setRedirect($url);
        }
    }

    public function addPrintButton($observer)
    {
        $block = $observer->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Shipment_View) {
            $shipmentId = Mage::app()->getRequest()->getParam('shipment_id');
            $block->addButton('do_something_crazy', array(
                'label'   => 'Export Order',
                'onclick' => 'setLocation(\' ' . Mage::helper('mercadopago_mercadoenvios')->getTrackingPrintUrl($shipmentId) . '\')',
                'class'   => 'go'
            ));
        }
    }

}