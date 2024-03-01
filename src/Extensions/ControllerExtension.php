<?php

namespace Innoweb\GoogleAnalytics\Extensions;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\Controller;

class ControllerExtension extends Extension {

    public static function get_analytics_config()
    {
        if (class_exists('Symbiote\Multisites\Multisites')) {
            if (is_subclass_of(Controller::curr(), LeftAndMain::class)) {
                return \Symbiote\Multisites\Multisites::inst()->getActiveSite();
            } else {
                return \Symbiote\Multisites\Multisites::inst()->getCurrentSite();
            }
        } elseif (class_exists('Fromholdio\ConfiguredMultisites\Multisites')) {
            if (is_subclass_of(Controller::curr(), LeftAndMain::class)) {
                return \Fromholdio\ConfiguredMultisites\Multisites::inst()->getActiveSite();
            } else {
                return \Fromholdio\ConfiguredMultisites\Multisites::inst()->getCurrentSite();
            }
        } else {
            return SiteConfig::current_site_config();
        }
    }

    public function AnalyticsConfig()
    {
        return self::get_analytics_config();
    }

    public function ShowGoogleAnalytics()
    {
        $config = self::get_analytics_config();
        if (
            Director::isLive() &&
            $config && $config->exists() &&
            $config->GoogleAnalyticsID &&
            strpos($_SERVER['REQUEST_URI'], '/admin') === false &&
            strpos($_SERVER['REQUEST_URI'], '/Security') === false &&
            strpos($_SERVER['REQUEST_URI'], '/dev') === false) {
            return true;
        }
        return false;
    }

    /**
     * Return a custom url for the GA page view. Can be overwritten for page types
     * that allow different views on the same URL, i.e. multi step forms.
     * Should return false if default url is to be used.
     * Can return the URL to be used as String or in an array with "URL" => "Page Title".
     * The page title is only submitted if Universal Analytics or Google Tag Manager are used.
     * @return string|array|boolean
     */
    public function getCustomPageViewUrl()
    {
        return false;
    }

    public function getPageViewUrlData()
    {
        if ($this->ShowGoogleAnalytics()) {
            $config = self::get_analytics_config();
            if ($config && $config->exists()) {

                if ($config->GoogleAnalyticsType == 'Global Site Tag') {

                    if ($urldata = $this->owner->getCustomPageViewUrl()) {
                        if (is_array($urldata)) {
                            // check if associative array
                            if (array_keys($urldata) !== range(0, count($urldata) - 1)) {
                                foreach ($urldata as $url => $title) {
                                    $pageviews[] = array(
                                        'page_location' => $url,
                                        'page_title' => $title,
                                    );
                                }
                            } else {
                                foreach ($urldata as $url) {
                                    $pageviews[] = array(
                                        'page_location' => $url,
                                    );
                                }
                            }
                        } elseif (is_string($urldata)) {
                            $pageviews[] = array(
                                'page_location' => $urldata,
                            );
                        }
                        if (count($pageviews)) {
                            $tag = "<script>";
                            foreach ($pageviews as $pageview) {
                                $tag .= "gtag('event', 'page_view', { page_location: '".$pageview['virtualPageURL']."'";
                                if (isset($pageview['virtualPageTitle'])) {
                                    $tag .= ", page_title: '".$pageview['virtualPageTitle']."'";
                                }
                                $tag .= "});";
                            }
                            $tag .= "</script>";
                            return DBHTMLText::create()->setValue($tag);
                        }
                    }
                    return;

                } else if ($config->GoogleAnalyticsType == 'Google Tag Manager') {

                    $pageviews = array();
                    // virtual page view url
                    if ($urldata = $this->owner->getCustomPageViewUrl()) {
                        if (is_array($urldata)) {
                            // check if associative array
                            if (array_keys($urldata) !== range(0, count($urldata) - 1)) {
                                foreach ($urldata as $url => $title) {
                                    $pageviews[] = array(
                                        'virtualPageURL' => $url,
                                        'virtualPageTitle' => $title,
                                    );
                                }
                            } else {
                                foreach ($urldata as $url) {
                                    $pageviews[] = array(
                                        'virtualPageURL' => $url,
                                    );
                                }
                            }
                        } elseif (is_string($urldata)) {
                            $pageviews[] = array(
                                'virtualPageURL' => $urldata,
                            );
                        }
                        if (count($pageviews)) {
                            $tag = "<script>dataLayer = [];";
                            foreach ($pageviews as $pageview) {
                                $tag .= "dataLayer.push({'event': 'VirtualPageview','virtualPageURL': '".$pageview['virtualPageURL']."'";
                                if (isset($pageview['virtualPageTitle'])) {
                                    $tag .= ",'virtualPageTitle': '".$pageview['virtualPageTitle']."'";
                                }
                                $tag .= "});";
                            }
                            $tag .= "</script>";
                            return DBHTMLText::create()->setValue($tag);
                        }
                    }
                }
            }
        }
    }
}
