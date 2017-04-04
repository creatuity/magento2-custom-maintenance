<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\CustomMaintenance\Model\Maintenance\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RebuildAll extends AbstractObserver
{
    public function execute(Observer $observer)
    {
        try {
            $this->maintenance->rebuild();
        } catch (\Exception $e) {
            $this->errorHandler->handle($e, 'Problem with rebuilding maintenance views');
        }
    }
}
