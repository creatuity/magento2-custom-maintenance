<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\CustomMaintenance\Controller\Adminhtml\Preview;

use Creatuity\CustomMaintenance\Controller\Adminhtml\AbstractAction;
use Creatuity\CustomMaintenance\Block\Adminhtml\Maintenance\Page as MaintenancePage;

class Preview extends AbstractAction
{
    public function execute()
    {
        try {
            $output = $this->blockFactory
                ->createBlock(MaintenancePage::class, [
                    'data' => [
                        'store_id' => $this->getRequest()->getParam('store_id'),
                        'area' => 'frontend',
                    ],
                ])->toHtml();

            return $this->resultRawFactory->create()->setContents($output);
        } catch (\Exception $e) {
            $this->errorHandler->handle($e, 'Cannot generate preview');

            return $this->redirectReferralResponse();
        }
    }
}
