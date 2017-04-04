<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\Error;

use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\Response\Http as Response;
use Magento\Framework\Error\Processor as BaseProcessor;
use Magento\Store\Model\StoreManager;
use Psr\Log\LoggerInterface;

class Processor extends BaseProcessor
{
    const REQUEST_MAINTENANCE = 'maintenance';
    const REQUEST_COUNTDOWN = 'countdown';
    const REQUEST_CORE = 'core';

    protected $template = 'Creatuity_CustomMaintenance::maintenance_page.phtml';
    protected $countdownConfigFile = BP . '/var/.maintenance.flag';

    protected $magentoConfig;
    protected $templatePath;
    protected $requestType;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(Request $request, Response $response, LoggerInterface $logger)
    {
        $this->_errorDir = __DIR__ . '/';

        if (!$this->prepareMyConfig()) {
            $this->requestType = self::REQUEST_CORE;
            return parent::__construct($response);
        }

        $this->_response = $response;
        $this->logger = $logger;
        $this->requestType = $request->has('creatuity_maintenance_countdown_data')
            ? self::REQUEST_COUNTDOWN
            : self::REQUEST_MAINTENANCE;
    }

    public function process()
    {
        switch ($this->requestType) {
            case self::REQUEST_CORE:
                return $this->process503();
            case self::REQUEST_MAINTENANCE:
                return $this->processMaintenancePage();
            case self::REQUEST_COUNTDOWN:
                return $this->processCountdownData();
        }
    }

    protected function processMaintenancePage()
    {
        $content = \file_get_contents($this->templatePath);

        return $this->response(503, $content ? $content : 'Service Temporary Unavailable');
    }

    protected function processCountdownData()
    {
        $countdownTimestamp = $this->calculateCountdownTimestamp();

        if (!$countdownTimestamp) {
            return $this->response(500, 'Cannot calculate time');
        }

        $body = "var myCountdown = new Countdown({"
            . 'rangeHi: "hour" ,'
            . 'hideLine: true ,'
            . "time: {$countdownTimestamp},"
            . 'labels:{'
            . 'font : "Lato-Heavy"'
            . '}'
            . '});';

        return $this->response(200, $body, 'application/javascript');
    }

    protected function prepareMyConfig()
    {
        $this->magentoConfig = $this->readConfig();
        if (!$this->magentoConfig) {
            $this->logError("Cannot load config");

            return false;
        }

        $storeCode = $this->determineStoreCode();
        if (!$storeCode) {
            $this->logError("Cannot determine store code");

            return false;
        }

        $skinDir = $this->determineSkin($storeCode);
        if (!$skinDir) {
            $this->logError("Cannot determine skindir for '{$storeCode}'");

            return false;
        }

        $this->templatePath = $this->_errorDir . $skinDir . '/' . $this->template;
        if (!\file_exists($this->templatePath) || !\is_readable($this->templatePath)) {
            $this->logError("Cannot read file: '{$this->templatePath}'");

            return false;
        }

        return true;
    }

    protected function readConfig()
    {
        $countdownConfigFile = $this->_errorDir . 'processor.data.json';

        if (!file_exists($countdownConfigFile)) {
            return false;
        }

        $jsonConfig = @\file_get_contents($countdownConfigFile);

        if (!is_string($jsonConfig) || empty($jsonConfig)) {
            return false;
        }

        $config = @\json_decode($jsonConfig, true);

        return is_array($config) ? $config : false;
    }

    protected function determineSkin($storeCode)
    {
        if (empty($this->magentoConfig['stores'][$storeCode]['website'])) {
            return null;
        }
        $websiteCode = $this->magentoConfig['stores'][$storeCode]['website'];

        return "maintenance_store_{$websiteCode}_{$storeCode}";
    }

    protected function determineStoreCode()
    {
        if (!empty($GLOBALS['CREATUITY_MAINTENANCE_STORE'])) {
            return $GLOBALS['CREATUITY_MAINTENANCE_STORE'];
        }

        if (defined('CREATUITY_MAINTENANCE_STORE')) {
            return CREATUITY_MAINTENANCE_STORE;
        }

        if (!empty($_COOKIE['store'])) {
            return $_COOKIE['store'];
        }

        $mageRunType = isset($_SERVER[StoreManager::PARAM_RUN_TYPE])
            ? $_SERVER[StoreManager::PARAM_RUN_TYPE]
            : 'store';

        if ($mageRunType === 'store' && !empty($_SERVER[StoreManager::PARAM_RUN_CODE])) {
            return $_SERVER[StoreManager::PARAM_RUN_CODE];
        }

        if ($mageRunType === 'website' && !empty($_SERVER[StoreManager::PARAM_RUN_CODE])) {
            return $this->defaultStoreForWebsite($_SERVER[StoreManager::PARAM_RUN_CODE]);
        }

        return $this->defaultStore();
    }

    protected function defaultStoreForWebsite($websiteCode)
    {
        if (!empty($this->magentoConfig['websites'][$websiteCode]['default-store'])) {
            return $this->magentoConfig['websites'][$websiteCode]['default-store'];
        }

        return $this->defaultStore();
    }

    protected function defaultStore()
    {
        if (!empty($this->magentoConfig['default-store'])) {
            return $this->magentoConfig['default-store'];
        }

        return null;
    }

    protected function response($code, $body, $type = 'text/html')
    {
        return $this->_response
            ->setHttpResponseCode($code)
            ->setBody($body)
            ->setHeader('Content-Type', $type);
    }

    protected function calculateCountdownTimestamp()
    {
        $configDate = $this->readCountdownConfig();
        if (!$configDate) {
            return null;
        }

        $serverTimeInGMT = getdate();
        $maintenanceOffTimeInGMT = strtotime($configDate);

        if (!is_numeric($maintenanceOffTimeInGMT)) {
            return null;
        }

        return $maintenanceOffTimeInGMT - $serverTimeInGMT[0];
    }

    protected function readCountdownConfig()
    {
        if (!file_exists($this->countdownConfigFile)) {
            return false;
        }

        return trim(file_get_contents($this->countdownConfigFile));
    }

    protected function logError($error)
    {
        $this->logger->critical('Creatuity Custom Maintenance Error: ' . $error);
    }
}
