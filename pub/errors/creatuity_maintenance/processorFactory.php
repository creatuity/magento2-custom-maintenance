<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\Error;

use Magento\Framework\App\Bootstrap;

require_once BP . '/app/bootstrap.php';
require_once BP . '/pub/errors/processor.php';
require_once BP . '/pub/errors/creatuity_maintenance/processor.php';

class ProcessorFactory
{    
    public function createProcessor()
    {
        $objectManagerFactory = Bootstrap::createObjectManagerFactory(BP, $_SERVER);
        $objectManager = $objectManagerFactory->create($_SERVER);
        $request = $objectManager->create('Magento\Framework\App\Request\Http');
        $response = $objectManager->create('Magento\Framework\App\Response\Http');
        $logger = $objectManager->create('Psr\Log\LoggerInterface');

        return new Processor($request, $response, $logger);
    }
}
