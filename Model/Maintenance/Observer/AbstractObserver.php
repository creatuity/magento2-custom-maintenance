<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\CustomMaintenance\Model\Maintenance\Observer;

use Creatuity\CustomMaintenance\Model\Adminhtml\Error\Handler;
use Creatuity\CustomMaintenance\Model\Maintenance;
use Magento\Framework\Event\ObserverInterface;

abstract class AbstractObserver implements ObserverInterface
{
    /** @var Maintenance */
    protected $maintenance;

    /** @var Handler */
    protected $errorHandler;

    public function __construct(Maintenance $maintenance, Handler $errorHandler)
    {
        $this->maintenance = $maintenance;
        $this->errorHandler = $errorHandler;
    }
}
