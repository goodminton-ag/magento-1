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
 * @package         js
 * @subpackage      payone
 * @copyright       Copyright (c) 2013 <info@noovias.com> - www.noovias.com
 * @author          Alexander Dite <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

function payoneKlarnaCustomerDobInput(output_element)
{
    var daySelect = $('payone_klarna_base_additional_fields_customer_dob_day');
    var monthSelect = $('payone_klarna_base_additional_fields_customer_dob_month');
    var yearSelect = $('payone_klarna_base_additional_fields_customer_dob_year');
    var hiddenDobFull = $(output_element);

    if (daySelect == undefined || monthSelect == undefined || yearSelect == undefined
        || hiddenDobFull == undefined)  {
        return;
    }

    hiddenDobFull.value = yearSelect.value + "-" + monthSelect.value + "-" + daySelect.value;
}

function payoneKlarnaStartSession(url, methodCode) {
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("POST", url, false);

    xmlhttp.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    var parameters = "method=" + methodCode;

    if (document.getElementById('isAdminOrder')) {
        var isAdmin = document.getElementById('isAdminOrder').value;
        parameters += "&isAdmin=" + isAdmin
    }
    if (document.getElementById('quoteId')) {
        var quoteId = document.getElementById('quoteId').value;
        parameters += "&quoteId=" + quoteId
    }
    if (document.getElementById('payone_klarna_base_additional_fields_customer_dob_full')) {
        var dob = document.getElementById('payone_klarna_base_additional_fields_customer_dob_full').value;
        parameters += "&dob=" + dob
    }

    xmlhttp.send(parameters);

    if (xmlhttp.responseText != null) {
        try {
            return JSON.parse(xmlhttp.responseText);
        } catch (e) {
            console.debug(e);
            return {
                status: 'ERROR',
                customer_message: 'An error occured duing the operation.'
            };
        }
    }
}

function payoneKlarnaSwitchOverlay(show) {
    if (typeof show == 'undefined') {
        show = false;
    }

    var overlay = jQuery('#payone_klarna_method_overlay');

    if (show) {
        overlay.show();
    } else {
        overlay.hide();
    }
}
