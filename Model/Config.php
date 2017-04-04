<?php
/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */

namespace Creatuity\CustomMaintenance\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    protected $storeId = null;


    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function setStoreId($id)
    {
        $this->storeId = $id;

        return $this;
    }

    public function getLogoUrl()
    {
        return $this->config('general/logo');
    }

    public function getBackgroundColor()
    {
        return (string) $this->config('general/background_color');
    }

    public function getSideColor()
    {
        return (string) $this->config('general/side_background_color');
    }

    public function getFooterAndHeaderColor()
    {
        return (string) $this->config('general/footer_and_header_background_color');
    }

    public function getTitle()
    {
        return (string) $this->config('general/title');
    }

    public function getCopyright()
    {
        return (string) $this->config('general/copyright');
    }

    public function getNotification()
    {
        return (string) $this->config('general/notification_text');
    }

    public function getPhoneNumber()
    {
        return (string) $this->config('contact/phone_number');
    }

    public function getEmail()
    {
        return (string) $this->config('contact/email');
    }

    public function getTitleFontColor()
    {
        return (string) $this->config('general/title_font_color');
    }

    public function getCopyrightFontColor()
    {
        return (string) $this->config('general/copyright_color');
    }

    public function getNotificationFontColor()
    {
        return (string) $this->config('general/notification_font_color');
    }

    public function areTooltipsEnabled()
    {
        return (bool) $this->config('general/enable_tooltips');
    }

    public function getEmailTooltip()
    {
        return (string) $this->config('general/email_tooltip');
    }

    public function getPhoneTooltip()
    {
        return (string) $this->config('general/phone_tooltip');
    }

    public function isCounterAnimationEnabled()
    {
        return (bool) $this->config('general/counter_animation');
    }

    public function isTitleAnimationEnabled()
    {
        return (bool) $this->config('general/title_animation');
    }

    public function checkIfRotatingIconsEnabled()
    {
        return (bool) $this->config('social_media/rotating_icons');
    }

    public function isFacebookEnabled()
    {
        return (bool) $this->config('social_media/enable_facebook');
    }

    public function getFacebookPageUrl()
    {
        return $this->config('social_media/facebook');
    }

    public function isTwitterEnabled()
    {
        return (bool) $this->config('social_media/enable_twitter');
    }

    public function getTwitterPageUrl()
    {
        return $this->config('social_media/twitter');
    }

    public function isYoutubeEnabled()
    {
        return (bool) $this->config('social_media/enable_youtube');
    }

    public function getYoutubePageUrl()
    {
        return $this->config('social_media/youtube');
    }

    public function isGoogleEnabled()
    {
        return (bool) $this->config('social_media/enable_google');
    }

    public function getGooglePageUrl()
    {
        return $this->config('social_media/google');
    }

    protected function config($code)
    {
        return $this->scopeConfig->getValue("creatuity_custommaintenance/{$code}", ScopeInterface::SCOPE_STORE, $this->storeId);
    }
}
