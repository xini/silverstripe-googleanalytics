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

		// List of rules that have been created in all stages
		$configs = SiteConfig::get()->filter(array("Upgraded" => false));
		foreach($configs as $config) {
			$this->upgradeConfig($config);
		}
	}

	protected function upgradeConfig(SiteConfig $config) {
		$this->log("Upgrading site config ID = ".$config->ID);

		if ($config->GoogleAnalyticsUseUniversalAnalytics) {
		    $config->GoogleAnalyticsType = 'Universal Analytics';
		} else {
		    $config->GoogleAnalyticsType = 'Old Asynchronous Analytics';
		}
		$config->Upgraded = true;
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
