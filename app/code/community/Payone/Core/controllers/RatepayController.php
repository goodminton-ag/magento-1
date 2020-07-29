<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 */

/**
 * Class Payone_Core_RatepayController
 */
class Payone_Core_RatepayController extends Mage_Core_Controller_Front_Action
{
    /**
     * Calculates the rates by from user defined rate
     * called from an ajax request with ratePay parameters (ratepay.js)
     * map RatePay API parameters and request the payone API
     *
     */
    public function rateAction()
    {
        $html = '';
        $paymentMethod = $this->getRequest()->getParam('paymentMethod');
        $calcValue = $this->getRequest()->getParam('calcValue');
        $ratePayShopId = $this->getRequest()->getParam('ratePayshopId');
        $amount = $this->getRequest()->getParam('amount');
        $ratePayCurrency = $this->getRequest()->getParam('ratePayCurrency');
        $isAdmin = $this->getRequest()->getParam('isAdmin');
        $quoteId = $this->getRequest()->getParam('quoteId');
        $this->loadLayout();

        try {
            if (preg_match('/^[0-9]+(\.[0-9][0-9][0-9])?(,[0-9]{1,2})?$/', $calcValue)) {
                $calcValue = str_replace(".", "", $calcValue);
                $calcValue = str_replace(",", ".", $calcValue);
                $calcValue = floor($calcValue * 100);  //MAGE-363: Use cent instead of floating currency

                $client = Mage::getSingleton('payone_core/mapper_apiRequest_payment_genericpayment');
                $ratePayConfigModel = Mage::getSingleton('payone_core/payment_method_ratepay');
                $getConfig = $this->getConfig($ratePayConfigModel, $isAdmin, $quoteId);
                $result = $client->ratePayCalculationRequest($amount, $ratePayShopId, $ratePayCurrency, $calcValue, null, $getConfig, 'calculation-by-rate');

                if ($result instanceof Payone_Api_Response_Genericpayment_Ok) {
                    $responseData = $result->getPayData()->toAssocArray();
                    $initialRateChoice = $this->getRequest()->getParam('calcValue');
                    $message = $this->__('lang_calculation_rate_ok');

                    // if the calculated installment value is different from the choice, we notify the user
                    if ($initialRateChoice != $responseData['rate']) {
                        // if value is lower than choice AND number of months is at maximum (36)
                        // then the choice was too low
                        // otherwise, it just got adapted to the closest available installment value
                        if (
                            $initialRateChoice < $responseData['rate']
                            && $responseData['number-of-rates'] == 36
                        ) {
                            $message = $this->__('lang_calculation_rate_too_low');
                        } else {
                            $message = $this->__('lang_calculation_rate_not_available');
                        }
                    }
                    $responseData['calculation-result-message'] = $message;

                    /** @var Payone_Core_Block_Checkout_RatePayInstallmentplan $reviewBlock */
                    $reviewBlock = $this->getLayout()->getBlock('payone_ratepay.checkout.installmentplan');
                    $reviewBlock->setData($responseData);
                    $reviewBlock->setIsAdmin($isAdmin);
                    $html = $reviewBlock->toHtml();

                    //if admin order, some fields are added to store,
                    //otherwise, data are stores into session
                    if (!$isAdmin) {
                        //set payone Session Data
                        $this->setSessionData($responseData, $paymentMethod);
                    }
                } else {
                    $this->unsetSessionData($paymentMethod);
                    if($result instanceof Payone_Api_Response_Error) {
                        $html = "<div class='ratepay-result rateError'>" . $this->__($result->getCustomermessage()) . "</div>";
                    }
                }
            } else {
                $this->unsetSessionData($paymentMethod);
                $html = "<div class='ratepay-result rateError'>" . $this->__('lang_error') . ":<br/>" . $this->__('lang_wrong_value') . "</div>";
            }
        } catch (Exception $e) {
            $this->unsetSessionData($paymentMethod);
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Unable to initialize Rate Pay Installement.')
            );
            Mage::logException($e);
        }
        
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'text/html', true)
            ->setBody($html);
        return;
    }

    /**
     * Calculates the rates by from user defined runtime
     * called from an ajax request with ratePay parameters (ratepay.js)
     * map RatePay API parameters and request the payone API
     */
    public function runtimeAction()
    {
        $paymentMethod = $this->getRequest()->getParam('paymentMethod');
        $calcValue = $this->getRequest()->getParam('calcValue');
        $ratePayShopId = $this->getRequest()->getParam('ratePayshopId');
        $amount = $this->getRequest()->getParam('amount');
        $ratePayCurrency = $this->getRequest()->getParam('ratePayCurrency');
        $isAdmin = $this->getRequest()->getParam('isAdmin');
        $quoteId = $this->getRequest()->getParam('quoteId');
        $this->loadLayout();

        try {
                if (preg_match('/^[0-9]{1,5}$/', $calcValue)) {
                    $client = Mage::getSingleton('payone_core/mapper_apiRequest_payment_genericpayment');
                    $ratePayConfigModel = Mage::getSingleton('payone_core/payment_method_ratepay');
                    $getConfig = $this->getConfig($ratePayConfigModel, $isAdmin, $quoteId);
                    $result = $client->ratePayCalculationRequest($amount, $ratePayShopId, $ratePayCurrency, null, $calcValue, $getConfig, 'calculation-by-time');

                    if ($result instanceof Payone_Api_Response_Genericpayment_Ok) {
                        $responseData = $result->getPayData()->toAssocArray();
                        $message = $this->__('lang_calculation_runtime_ok');

                        // if the calculated runtime value is different from the choice, we notify the user
                        if ($responseData['number-of-rates'] != $calcValue) {
                            $message = $this->__('lang_calculation_runtime_not_available');
                        }
                        $responseData['calculation-result-message'] = $message;

                        /** @var Payone_Core_Block_Checkout_RatePayInstallmentplan $reviewBlock */
                        $reviewBlock = $this->getLayout()->getBlock('payone_ratepay.checkout.installmentplan');
                        $reviewBlock->setData($responseData);
                        $reviewBlock->setIsAdmin($isAdmin);
                        $html = $reviewBlock->toHtml();

                        //if admin order, some fields are added to store,
                        //otherwise, data are stores into session
                        if (!$isAdmin) {
                            //set payone Session Data
                            $this->setSessionData($responseData, $paymentMethod);
                        }
                    } else {
                        $this->unsetSessionData($paymentMethod);
                        $html = "<div class='rateError'>" . $this->__('lang_error') . ":<br/>" . $this->__('lang_request_error_else') . "</div>";
                    }
                } else {
                    $this->unsetSessionData($paymentMethod);
                    $html = "<div class='rateError'>" . $this->__('lang_error') . ":<br/>" . $this->__('lang_wrong_value') . "</div>";
                }
        } catch (Exception $e) {
            $this->unsetSessionData($paymentMethod);
            Mage::getSingleton('checkout/session')->addError(
                $this->__('Unable to initialize Rate Pay Installement.')
            );
            Mage::logException($e);
        }
        
        $this->getResponse()
            ->clearHeaders()
            ->setHeader('Content-Type', 'text/html', true)
            ->setBody($html);
        return;
    }

    /**
     * Payone session instance getter
     *
     * @return Payone_Core_Model_Session
     */
    private function _getSession()
    {
        return Mage::getSingleton('payone_core/session');
    }

    /**
     * Set the calculated rates into the session
     *
     * @param array $result
     */
    private function setSessionData($result, $paymentMethod)
    {
        foreach ($result as $key => $value) {
            $setSessionFunction = "set".$paymentMethod . ucfirst($key);
            Mage::getSingleton('payone_core/session')->$setSessionFunction($value);
        }
    }

    /**
     * Unsets the calculated rates from the session
     */
    private function unsetSessionData($paymentMethod)
    {
        foreach (Mage::getSingleton('payone_core/session')->getData() as $key => $value) {
            if (!is_array($value)) {
                $sessionNameBeginning = substr($key, 0, strlen($paymentMethod));
                if ($sessionNameBeginning == $paymentMethod && $key[strlen($paymentMethod)] == "_") {
                    $unsetFunction = "uns" . $key;
                    Mage::getSingleton('payone_core/session')->$unsetFunction();
                }
            }
        }
    }

    /**
     * Retrieve quote
     *
     * @return Mage_Sales_Model_Quote
     */
    private function getQuote()
    {
        return Mage::getSingleton('checkout/session')->getQuote();
    }

    /**
     * @param Payone_Core_Model_Payment_Method_Ratepay $ratePayConfigModel
     * @param bool $isAdmin
     * @param string $quoteId
     * @return Payone_Core_Model_Config_Payment_Method_Interface
     */
    private function getConfig($ratePayConfigModel, $isAdmin = false, $quoteId = '')
    {
        if ($isAdmin) {
            $quote = Mage::getModel('sales/quote')->load($quoteId);
        }
        else {
            $quote = $this->getQuote();
        }

        return $ratePayConfigModel->getAllConfigsByQuote($quote);
    }
}