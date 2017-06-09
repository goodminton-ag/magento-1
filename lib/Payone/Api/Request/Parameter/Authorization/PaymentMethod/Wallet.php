<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPL 3)
 * that is bundled with this package in the file LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Payone to newer
 * versions in the future. If you wish to customize Payone for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Api
 * @subpackage      Request
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet
    extends Payone_Api_Request_Parameter_Authorization_PaymentMethod_Abstract
{
    /**
     * @var string
     */
    protected $wallettype = NULL;
    /**
     * @var string
     */
    protected $successurl = NULL;
    /**
     * @var string
     */
    protected $errorurl = NULL;
    /**
     * @var string
     */
    protected $backurl = NULL;

    protected $paydata = NULL;

    /**
     * @var string
     */
    protected $recurring = NULL;

    /**
     * @var string
     */
    protected $customer_is_present = NULL;

    /**
     * @param $successurl
     */
    public function setSuccessurl($successurl)
    {
        $this->successurl = $successurl;
    }

    /**
     * @return string
     */
    public function getSuccessurl()
    {
        return $this->successurl;
    }

    /**
     * @param $wallettype
     */
    public function setWallettype($wallettype)
    {
        $this->wallettype = $wallettype;
    }

    /**
     * @return string
     */
    public function getWallettype()
    {
        return $this->wallettype;
    }

    /**
     * @param string $backurl
     */
    public function setBackurl($backurl)
    {
        $this->backurl = $backurl;
    }

    /**
     * @return string
     */
    public function getBackurl()
    {
        return $this->backurl;
    }

    /**
     * @param string $errorurl
     */
    public function setErrorurl($errorurl)
    {
        $this->errorurl = $errorurl;
    }

    /**
     * @return string
     */
    public function getErrorurl()
    {
        return $this->errorurl;
    }
    
    /**
     * @param Payone_Api_Request_Parameter_Paydata_Paydata $paydata
     */
    public function setPaydata($paydata) 
    {
        $this->paydata = $paydata;
    }

    /**
     * 
     * @return Payone_Api_Request_Parameter_Paydata_Paydata
     */
    public function getPaydata() 
    {
        return $this->paydata;
    }

    /**
     * @param $customerIsPresent
     */
    public function setCustomerIsPresent($customerIsPresent)
    {
        $this->customer_is_present = $customerIsPresent;
    }

    /**
     * @return string
     */
    public function getCustomerIsPresent()
    {
        return $this->customer_is_present;
    }

    /**
     * @param $recurring
     */
    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;
    }

    /**
     * @return string
     */
    public function getRecurring()
    {
        return $this->recurring;
    }
    
}
