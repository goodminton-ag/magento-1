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
 * Do not edit or add to this file if you wish to upgrade Payone_Core to newer
 * versions in the future. If you wish to customize Payone_Core for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      System
 * @copyright       Copyright (c) 2019 <kontakt@fatchip.de> - www.fatchip.com
 * @author          Vincent Boulanger <vincent.boulanger@fatchip.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.fatchip.com, https://e3n.de/
 */

/**
 * Class Payone_Core_Model_System_Config_RatepayInvoicingType
 */
class Payone_Core_Model_System_Config_RatepayInvoicingType extends Payone_Core_Model_System_Config_Abstract
{
     /**
     * @return array
     */
    public function toArray()
    {
        $settings = new Payone_Settings_Configuration_PaymentMethod_RatePayInvoicing();
        return $settings->getTypes();
    }
}