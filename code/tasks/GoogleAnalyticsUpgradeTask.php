<?php

/**
 * Assists with upgrade of google analytics to 2.0
 */
class GoogleAnalyticsUpgradeTask extends BuildTask {

	protected $title = "Google Analytics 2.0 Migration Tool";

	protected $description = "Upgrade tool for sites upgrading to Google Analytics 2.0";

	public function run($request) {
		$service = Injector::inst()->create('GoogleAnalyticsUpgradeService');
		$service->log("Upgrading googleanalytics module");
		$service->setQuiet(false)
			->run();
		$service->log("Done");
	}
}
