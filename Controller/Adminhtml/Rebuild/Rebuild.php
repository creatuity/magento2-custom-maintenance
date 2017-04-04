<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\CustomMaintenance\Controller\Adminhtml\Rebuild;

use Creatuity\CustomMaintenance\Controller\Adminhtml\AbstractAction;

class Rebuild extends AbstractAction
{
    public function execute()
    {
        try {
            $this->maintenance->rebuild();

            $this->messageManager->addSuccessMessage('Custom Maintenance Page successfully rebuilt');
        } catch (\Exception $e) {
            $this->errorHandler->handle($e, 'Unknown problem with rebuilding maintenance views');
        }

        return $this->redirectReferralResponse();
    }
}
