<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\CustomMaintenance\Plugin\App;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Http as HttpApp;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;

class Http
{
    const MAINTENANCE_PAGE_PATH = 'errors/creatuity_maintenance/503.php';

    /** @var Filesystem */
    protected $filesystem;

    /** @var LoggerInterface */
    protected $logger;


    public function __construct(Filesystem $filesystem, LoggerInterface $logger)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    public function aroundCatchException(HttpApp $httpApp, \Closure $proceed, Bootstrap $bootstrap, \Exception $exception)
    {
        if (Bootstrap::ERR_MAINTENANCE == $bootstrap->getErrorCode()) {
            $pubDirectory = $this->filesystem->getDirectoryRead(DirectoryList::PUB);
            $maintenanceEntry = $pubDirectory->getAbsolutePath(self::MAINTENANCE_PAGE_PATH);

            if ($pubDirectory->isExist(self::MAINTENANCE_PAGE_PATH)) {
                require $maintenanceEntry;

                return true;
            } else {
                $this->logger->error('Cannot find file ' . $maintenanceEntry);
            }
        }

        return $proceed($bootstrap, $exception);
    }
}
