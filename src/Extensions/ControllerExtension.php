<?php

declare(strict_types=1);

namespace Innoweb\GoogleAnalytics\Extensions;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\SiteConfig\SiteConfig;

class ControllerExtension extends Extension
{

    public static function get_analytics_config()
    {
        if (class_exists('Symbiote\Multisites\Multisites')) {
            if (is_subclass_of(Controller::curr(), LeftAndMain::class)) {
                return \Symbiote\Multisites\Multisites::inst()->getActiveSite();
            }

            return \Symbiote\Multisites\Multisites::inst()->getCurrentSite();
        }

        if (class_exists('Fromholdio\ConfiguredMultisites\Multisites')) {
            if (is_subclass_of(Controller::curr(), LeftAndMain::class)) {
                return \Fromholdio\ConfiguredMultisites\Multisites::inst()->getActiveSite();
            }

            return \Fromholdio\ConfiguredMultisites\Multisites::inst()->getCurrentSite();
        }

        return SiteConfig::current_site_config();
    }

    public function AnalyticsConfig()
    {
        return self::get_analytics_config();
    }

    public function ShowGoogleAnalytics(): bool
    {
        $config = self::get_analytics_config();
        return Director::isLive()
            && $config
            && $config->exists()
            && $config->GoogleAnalyticsID
            && !str_starts_with((string) $_SERVER['REQUEST_URI'], '/admin/')
            && !str_starts_with((string) $_SERVER['REQUEST_URI'], '/Security/')
            && !str_starts_with((string) $_SERVER['REQUEST_URI'], '/dev/');
    }

    /**
     * Return a custom url for the GA page view. Can be overwritten for page types
     * that allow different views on the same URL, i.e. multi step forms.
     * Should return false if default url is to be used.
     * Can return the URL to be used as String or in an array with "URL" => "Page Title".
     * The page title is only submitted if Universal Analytics or Google Tag Manager are used.
     * @return string|array|boolean
     */
    public function getCustomPageViewUrl(): bool
    {
        return false;
    }

    public function getPageViewUrlData()
    {
        if ($this->ShowGoogleAnalytics()) {
            $config = self::get_analytics_config();
            if ($config && $config->exists()) {
                if ($config->GoogleAnalyticsType == 'Global Site Tag') {
                    if ($urldata = $this->getOwner()->getCustomPageViewUrl()) {
                        if (is_array($urldata)) {
                            // check if associative array
                            if (array_keys($urldata) !== range(0, count($urldata) - 1)) {
                                foreach ($urldata as $url => $title) {
                                    $pageviews[] = [
                                        'page_location' => $url,
                                        'page_title' => $title,
                                    ];
                                }
                            } else {
                                foreach ($urldata as $url) {
                                    $pageviews[] = [
                                        'page_location' => $url,
                                    ];
                                }
                            }
                        } elseif (is_string($urldata)) {
                            $pageviews[] = [
                                'page_location' => $urldata,
                            ];
                        }

                        if ($pageviews !== []) {
                            $tag = "<script>";
                            foreach ($pageviews as $pageview) {
                                $tag .= "gtag('event', 'page_view', { page_location: '" . $pageview['virtualPageURL'] . "'";
                                if (isset($pageview['virtualPageTitle'])) {
                                    $tag .= ", page_title: '" . $pageview['virtualPageTitle'] . "'";
                                }

                                $tag .= "});";
                            }

                            $tag .= "</script>";
                            return DBHTMLText::create()->setValue($tag);
                        }
                    }

                    return;
                }

                if ($config->GoogleAnalyticsType == 'Google Tag Manager') {
                    $pageviews = [];
                    // virtual page view url
                    if ($urldata = $this->getOwner()->getCustomPageViewUrl()) {
                        if (is_array($urldata)) {
                            // check if associative array
                            if (array_keys($urldata) !== range(0, count($urldata) - 1)) {
                                foreach ($urldata as $url => $title) {
                                    $pageviews[] = [
                                        'virtualPageURL' => $url,
                                        'virtualPageTitle' => $title,
                                    ];
                                }
                            } else {
                                foreach ($urldata as $url) {
                                    $pageviews[] = [
                                        'virtualPageURL' => $url,
                                    ];
                                }
                            }
                        } elseif (is_string($urldata)) {
                            $pageviews[] = [
                                'virtualPageURL' => $urldata,
                            ];
                        }

                        if ($pageviews !== []) {
                            $tag = "<script>dataLayer = [];";
                            foreach ($pageviews as $pageview) {
                                $tag .= "dataLayer.push({'event': 'VirtualPageview','virtualPageURL': '" . $pageview['virtualPageURL'] . "'";
                                if (isset($pageview['virtualPageTitle'])) {
                                    $tag .= ",'virtualPageTitle': '" . $pageview['virtualPageTitle'] . "'";
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
