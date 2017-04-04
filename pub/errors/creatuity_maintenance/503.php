<?php

require_once 'processorFactory.php';
$processorFactory = new \Creatuity\Error\ProcessorFactory;
$processor = $processorFactory->createProcessor();
$response = $processor->process();
$response->sendResponse();
