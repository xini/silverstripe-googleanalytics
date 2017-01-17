<?php

/**
 * Service to support upgrade of userforms module
 */
class GoogleAnalyticsUpgradeService {

	/**
	 * @var bool
	 */
	protected $quiet;

	public function run() {
		$this->log("Upgrading settings");

		// get config class
		$currentConfig = GoogleAnalyticsControllerExtension::get_analytics_config();
		if ($currentConfig && $currentConfig->exists()) {
		    $class = $currentConfig->ClassName;
		
    		// List of rules that have been created in all stages
    		$configs = $class::get()->filter(array("GoogleAnalyticsUpgradedV2" => false, "GoogleAnalyticsType" => ""));
    		foreach($configs as $config) {
    			$this->upgradeConfig($config);
    		}
		}
	}

	protected function upgradeConfig($config) {
		$this->log("Upgrading config ID = ".$config->ID);

		if ($config->GoogleAnalyticsUseUniversalAnalytics) {
		    $config->GoogleAnalyticsType = 'Universal Analytics';
		} else {
		    $config->GoogleAnalyticsType = 'Old Asynchronous Analytics';
		}
		$config->GoogleAnalyticsUpgradedV2 = true;
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
	 * @return $ths
	 */
	public function setQuiet($quiet) {
		$this->quiet = $quiet;
		return $this;
	}

	public function getQuiet() {
		return $this->quiet;
	}

}
