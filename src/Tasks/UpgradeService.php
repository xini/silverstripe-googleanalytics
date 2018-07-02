<?php

namespace Innoweb\GoogleAnalytics\Tasks;

use SilverStripe\Control\Director;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * Service to support upgrade of userforms module
 */
class UpgradeService {

    /**
     * @var bool
     */
    protected $quiet;

    public function run() {
        $this->log('Upgrading settings');

        // get correct config class
        if (class_exists('Symbiote\Multisites\Multisites')) {
            $configs = \Symbiote\Multisites\Model\Site::get();
        } else {
            $configs = SiteConfig::get();
        }
        if ($configs && $configs->exists()) {

            // only upgrade configs that require it
            foreach($configs as $config) {
                if (
                    (!$config->GoogleAnalyticsUpgradedV2 && $config->GoogleAnalyticsType === '')
                    || $config->GoogleAnalyticsType === 'Old Asynchronous Analytics'
                ) {
                    $this->upgradeConfig($config);
                }
            }
        }
    }

    protected function upgradeConfig($config) {
        $this->log('Upgrading config ID = ' . $config->ID);

        if (!$config->GoogleAnalyticsUpgradedV2) {
            $config->GoogleAnalyticsType = 'Universal Analytics';
            $config->GoogleAnalyticsUpgradedV2 = true;
        }

        if ($config->GoogleAnalyticsType === 'Old Asynchronous Analytics') {
            $config->GoogleAnalyticsType = 'Universal Analytics';
        }

        $config->write();
    }

    public function log($message) {
        if($this->getQuiet()) {
            return;
        }
        if(Director::is_cli()) {
            echo "{$message}\n";
        } else {
            echo "{$message}<br />";
        }
    }

    /**
     * Set if this service should be quiet
     *
     * @param bool $quiet
     * @return $this
     */
    public function setQuiet($quiet) {
        $this->quiet = $quiet;
        return $this;
    }

    public function getQuiet() {
        return $this->quiet;
    }

}
