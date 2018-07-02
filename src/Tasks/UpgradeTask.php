<?php

namespace Innoweb\GoogleAnalytics\Tasks;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\BuildTask;

/**
 * Assists with upgrade of google analytics to 2.0
 */
class UpgradeTask extends BuildTask {

    protected $title = 'Google Analytics 2.0 Migration Tool';

    protected $description = 'Upgrade tool for sites upgrading to Google Analytics 2.0';

    public function run($request) {
        $service = Injector::inst()->create(UpgradeService::class);
        $service->log('Upgrading innoweb/googleanalytics module');
        $service->setQuiet(false)
            ->run();
        $service->log('Done');
    }
}
