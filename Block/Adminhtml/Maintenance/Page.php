<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\CustomMaintenance\Block\Adminhtml\Maintenance;

use Creatuity\CustomMaintenance\Model\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Page extends Template
{
    /** @var Config */
    protected $configModel;

    public function __construct(Config $configModel, Context $context, array $data = [])
    {
        $this->setTemplate('Creatuity_CustomMaintenance::maintenance_page.phtml');
        $this->configModel = $configModel;
        $this->configModel->setStoreId($data['store_id']);

        parent::__construct($context, $data);
    }

    public function getLogo()
    {
        return $this->configModel->getLogoUrl();
    }

    public function getBackgroundColor()
    {
        return $this->configModel->getBackgroundColor();
    }

    public function getSideColor()
    {
        return $this->configModel->getSideColor();
    }

    public function getFooterAndHeaderBackgroundColor()
    {
        return $this->configModel->getFooterAndHeaderColor();
    }

    public function getCopyright()
    {
        return $this->configModel->getCopyright();
    }

    public function getTitle()
    {
        return $this->configModel->getTitle();
    }

    public function getNotification()
    {
        return $this->configModel->getNotification();
    }

    public function getEmail()
    {
        return $this->configModel->getEmail();
    }

    public function getPhoneNumber()
    {
        return $this->configModel->getPhoneNumber();
    }

    public function getTitleFontColor()
    {
        return $this->configModel->getTitleFontColor();
    }

    public function getCopyrightFontColor()
    {
        return $this->configModel->getCopyrightFontColor();
    }

    public function getNotificationFontColor()
    {
        return $this->configModel->getNotificationFontColor();
    }

    public function areTooltipsEnabled()
    {
        return $this->configModel->areTooltipsEnabled();
    }

    public function getEmailTooltip()
    {
        return $this->configModel->getEmailTooltip();
    }

    public function getPhoneTooltip()
    {
        return $this->configModel->getPhoneTooltip();
    }

    public function isCounterAnimationEnabled()
    {
        return $this->configModel->isCounterAnimationEnabled();
    }

    public function isTitleAnimationEnabled()
    {
        return $this->configModel->isTitleAnimationEnabled();
    }

    public function checkIfRotatingIconsEnabled()
    {
        return $this->configModel->checkIfRotatingIconsEnabled();
    }

    public function isFacebookEnabled()
    {
        return $this->configModel->isFacebookEnabled();
    }

    public function getFacebookPageUrl()
    {
        return $this->configModel->getFacebookPageUrl();
    }

    public function isTwitterEnabled()
    {
        return $this->configModel->isTwitterEnabled();
    }

    public function getTwitterPageUrl()
    {
        return $this->configModel->getTwitterPageUrl();
    }

    public function isYoutubeEnabled()
    {
        return $this->configModel->isYoutubeEnabled();
    }

    public function getYoutubePageUrl()
    {
        return $this->configModel->getYoutubePageUrl();
    }

    public function isGoogleEnabled()
    {
        return $this->configModel->isGoogleEnabled();
    }

    public function getGooglePageUrl()
    {
        return $this->configModel->getGooglePageUrl();
    }

    public function internalUrl($path, array $params = [])
    {
        return $this->url("pub/errors/creatuity_maintenance/{$path}", $params);
    }

    public function defaultsUrl($path, array $params = [])
    {
        return $this->url("pub/errors/creatuity_maintenance/maintenance_creatuity_default/{$path}", $params);
    }

    public function url($path, array $params = [])
    {
        if ($path == '/') {
            $path = '';
        }

        return $this->getUrl(null, [
            '_direct' => $path,
            '_query' => $params
        ]);
    }
}
