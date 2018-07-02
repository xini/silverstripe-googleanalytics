<?php

namespace Innoweb\GoogleAnalytics\Extensions;

use Innoweb\GoogleAnalytics\Tasks\UpgradeService;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DB;
use UncleCheese\DisplayLogic\Forms\Wrapper;

class ConfigExtension extends DataExtension
{

    private static $db = [
        'GoogleAnalyticsType'               => 'Enum(array("","Universal Analytics","Google Tag Manager"), "")',
        'GoogleAnalyticsID'                 => 'Varchar',
        'GoogleAnalyticsCookieDomain'       => 'Varchar(255)',
        'GoogleAnalyticsUseEventTracking'   => 'Boolean',

        // legacy and upgrade fields
        'GoogleAnalyticsUseUniversalAnalytics'  => 'Boolean',
        'GoogleAnalyticsUpgradedV2'             => 'Boolean'
    ];

    private static $defaults = [
        'GoogleAnalyticsUpgradedV2'         =>  true
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $config = ControllerExtension::get_analytics_config();
        $fields->addFieldsToTab(
            'Root.GoogleAnalytics',
            [
                DropdownField::create(
                    "GoogleAnalyticsType",
                    _t('GoogleAnalyticsExtension.GoogleAnalyticsType', 'Google Analytics Type'),
                    singleton($config->ClassName)->dbObject('GoogleAnalyticsType')->enumValues()
                )->setDescription(
                    _t(
                        'GoogleAnalyticsExtension.TypeHelp',
                        "Please select the correct Analytics type according to the setup of your Google Analytics account."
                    )
                )->setEmptyString('none'),

                TextField::create('GoogleAnalyticsID', _t('GoogleAnalyticsExtension.GOOGLEANALYTICSID', 'Google Analytics ID')),

                $analyticsFields = Wrapper::create(
                    TextField::create(
                        'GoogleAnalyticsCookieDomain',
                        _t('GoogleAnalyticsExtension.NONSTANDARDCOOKIEDOMAIN', 'Non-Standard Cookie Domain')
                    )
                        ->setDescription(
                            _t(
                                'GoogleAnalyticsExtension.COOKIEDOMAINHELP',
                                "If you want to use a non-standard cookie domain for your tracking, please enter it here. If this field is left empty, 'auto' will be used."
                            )
                        ),

                    FieldGroup::create(
                        CheckboxField::create(
                            'GoogleAnalyticsUseEventTracking',
                            _t(
                                'GoogleAnalyticsExtension.EVENTTRACKINGHELP',
                                "Activate this box if you want to track events like downloads and clicks on external, email and phone links. "
                            )
                        )
                    )
                        ->setTitle(_t('GoogleAnalyticsExtension.USEEVENTTRACKING', 'Use Event Tracking'))
                        ->setName('GAEventTracking')
                )
            ]
        );
        $fields->fieldByName("Root.GoogleAnalytics")->setTitle(_t('GoogleAnalyticsExtension.GOOGLEANALYTICSTAB', 'Google Analytics'));

        $analyticsFields->displayIf('GoogleAnalyticsType')->isEqualTo('Universal Analytics');
    }

    public function updateSiteCMSFields(FieldList $fields) {
        $this->updateCMSFields($fields);
    }

    public function requireDefaultRecords() {

        // Perform migrations
        Injector::inst()
            ->create(UpgradeService::class)
            ->setQuiet(true)
            ->run();

        DB::alteration_message('Migrated innoweb/googleanalytics', 'changed');
    }

}
