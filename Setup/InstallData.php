<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\CustomMaintenance\Setup;

use Creatuity\CustomMaintenance\Model\Maintenance;
use Magento\Framework\App\State;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /** @var Maintenance */
    protected $maintenance;

    /** @var State */
    protected $appState;

    public function __construct(Maintenance $maintenance, State $appState)
    {
        $this->maintenance = $maintenance;
        $this->appState = $appState;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->appState->emulateAreaCode('frontend', function () {
            $this->maintenance->rebuild();
        });
    }
}
