<?php

namespace Creatuity\CustomMaintenance\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\View\Element\BlockFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */
class Maintenance
{
    /** @var string */
    protected $template = 'Creatuity_CustomMaintenance::maintenance_page.phtml';

    /** @var string */
    protected $baseDir = 'pub/errors/creatuity_maintenance/';

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var WriteInterface */
    protected $filesWrite;

    /** @var BlockFactory */
    protected $blockFactory;

    public function __construct(
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        BlockFactory $blockFactory,
        $baseDir = 'pub/errors/creatuity_maintenance/'
    ) {
        $this->storeManager = $storeManager;
        $this->blockFactory = $blockFactory;
        $this->filesWrite = $filesystem->getDirectoryWrite(DirectoryList::ROOT);
        $this->baseDir = rtrim($baseDir, '/') . '/';
    }

    public function rebuild()
    {
        $this->generateMagentoStoresConfigFile();
        $this->removeAllStores();
        $this->buildAllStores();
    }

    protected function removeAllStores()
    {
        foreach ($this->filesFindAllStoreDirs() as $dir) {
            $this->filesWrite->delete($dir);
        }
    }

    protected function buildAllStores()
    {
        foreach ($this->getStores() as $store) {
            $this->rebuildSingleStore($store);
        }
    }

    protected function rebuildSingleStore($store)
    {
        $content = $this->generateTemplate($store);
        $this->saveToFile($store, $this->template, $content);
    }

    protected function generateTemplate($store)
    {
        return $this->blockFactory
            ->createBlock('Creatuity\CustomMaintenance\Block\Adminhtml\Maintenance\Page', [
                'data' => [
                    'store_id' => $this->toStoreId($store),
                    'area' => 'frontend',
                ],
            ])
            ->toHtml();
    }

    protected function generateMagentoStoresConfigFile()
    {
        $config = [
            'stores' => [],
            'websites' => [],
            'default-store' => $this->storeManager->getDefaultStoreView()->getCode(),
        ];

        foreach ($this->getWebsites() as $website) {
            $config['websites'][$website->getCode()] = [
                'default-store' => $website->getDefaultStore()->getCode(),
            ];
        }

        foreach ($this->getStores() as $store) {
            $config['stores'][$store->getCode()] = [
                'website' => $this->toWebsiteCodeFromStore($store),
            ];
        }

        $this->filesSaveContentToFile('processor.data.json', json_encode($config));
    }

    protected function removePageDirectory($store)
    {
        $path = $this->pageFilePath($store);
        $this->filesDeleteFile($path);
    }

    protected function saveToFile($store, $fileName, $fileContent)
    {
        $this->filesSaveContentToFile(
            $this->pageFilePath($store, $fileName),
            $fileContent
        );
    }

    protected function pageFilePath($store, $path = null)
    {
        $storeCode = $this->toStoreCode($store);
        $websiteCode = $this->toWebsiteCodeFromStore($store);

        return "maintenance_store_{$websiteCode}_{$storeCode}/{$path}";
    }

    protected function filesFindAllStoreDirs()
    {
        return $this->filesWrite->search('maintenance_store_*_*', $this->baseDir);
    }

    protected function filesSaveContentToFile($filePath, $content)
    {
        $filePath = $this->baseDir . $filePath;

        $baseDir = dirname($filePath);
        $this->filesWrite->create($baseDir);
        if (!$this->filesWrite->isWritable($baseDir)) {
            throw new MaintenanceException(
                "'{$baseDir}' is not writable. Please make this path temporary writable. "
                . "Please do not forget to restore Your permissions!"
            );
        }
        $this->filesWrite->writeFile($filePath, $content);
    }

    protected function filesDeleteFile($path)
    {
        $this->filesWrite->delete($path);
    }

    protected function getWebsites()
    {
        return $this->storeManager->getWebsites();
    }

    protected function getStores()
    {
        return $this->storeManager->getStores();
    }

    protected function toStoreId($store)
    {
        return $this->storeManager->getStore($store)->getId();
    }

    protected function toStoreCode($store)
    {
        return $this->storeManager->getStore($store)->getCode();
    }

    protected function toWebsiteCode($website)
    {
        return $this->storeManager->getStore($website)->getCode();
    }

    protected function toWebsiteCodeFromStore($store)
    {
        return $this->storeManager->getStore($store)->getWebsite()->getCode();
    }
}
