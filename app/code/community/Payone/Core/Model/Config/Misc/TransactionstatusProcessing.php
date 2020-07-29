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
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Config
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Config_Misc_TransactionstatusProcessing
    extends Payone_Core_Model_Config_AreaAbstract
{
    /**
     * @var string
     */
    protected $cron_expr = '';

    /** @var array */
    protected $valid_ips = '';

    /** @var int */
    protected $proxy_mode = 0;

    /** @var int */
    protected $logging_active = 0;

    /** @var int */
    protected $reporting_active = 0;

    /** @var string */
    protected $report_email = '';

    /** @var int */
    protected $retries = 0;

    public function init(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key == 'valid_ips' and is_string($value)) {
                $value = $this->initValidIpsByString($value);
            }

            $this->setValue($key, $value);
        }
    }

    /**
     * @param $validIpString
     * @return array
     */
    protected function initValidIpsByString($validIpString)
    {
        $validIps = explode("\n", $validIpString);

        foreach ($validIps as $key => $ip) {
            if (empty($ip)) {
                unset($validIps[$key]);
            }
            else {
                $validIps[$key] = trim($ip);
            }
        }

        return $validIps;
    }

    /**
     * @param string $cron_expr
     */
    public function setCronExpr($cron_expr)
    {
        $this->cron_expr = $cron_expr;
    }

    /**
     * @return string
     */
    public function getCronExpr()
    {
        return $this->cron_expr;
    }

    /**
     * @param array $valid_ips
     */
    public function setValidIps($valid_ips)
    {
        $this->valid_ips = $valid_ips;
    }

    /**
     * @return array
     */
    public function getValidIps()
    {
        return $this->valid_ips;
    }

    /**
     * @param int $proxy_mode
     */
    public function setProxyMode($proxy_mode)
    {
        $this->proxy_mode = $proxy_mode;
    }

    /**
     * @return int
     */
    public function getProxyMode()
    {
        return $this->proxy_mode;
    }

    /**
     * @param int $logging_active
     */
    public function setLoggingActive($logging_active)
    {
        $this->logging_active = $logging_active;
    }

    /**
     * @return int
     */
    public function getLoggingActive()
    {
        return $this->logging_active;
    }

    /**
     * @param $reporting_active
     */
    public function setReportingActive($reporting_active)
    {
        $this->reporting_active = (int) $reporting_active;
    }

    /**
     * @return int
     */
    public function getReportingActive()
    {
        return (int) $this->reporting_active;
    }

    /**
     * @return string
     */
    public function getReportEmail()
    {
        return $this->report_email;
    }

    /**
     * @param string $report_email
     */
    public function setReportEmail($report_email)
    {
        $this->report_email = $report_email;
    }

    /**
     * @return int
     */
    public function getRetries()
    {
        return (int) $this->retries;
    }

    /**
     * @param int $retries
     */
    public function setRetries($retries)
    {
        $this->retries = (int) $retries;
    }
}
