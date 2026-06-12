<?php

declare(strict_types=1);

namespace Innoweb\GoogleAnalytics\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class ConfigExtension extends Extension
{


    private static array $db = [
        'GoogleAnalyticsType' => 'Enum(",Global Site Tag,Google Tag Manager", "")',
        'GoogleAnalyticsID' => 'Varchar',
    ];

    public function updateCMSFields(FieldList $fields): void
    {
        $config = ControllerExtension::get_analytics_config();
        $fields->addFieldsToTab(
            'Root.GoogleAnalytics',
            [
                DropdownField::create(
                    "GoogleAnalyticsType",
                    _t('GoogleAnalyticsExtension.GoogleTagType', 'Tag Type'),
                    singleton($config->ClassName)->dbObject('GoogleAnalyticsType')->enumValues()
                )->setDescription(
                    _t(
                        'GoogleAnalyticsExtension.TypeHelp',
                        "Please select the correct Analytics type according to the setup of your Google Analytics account."
                    )
                )->setEmptyString('none'),

                TextField::create('GoogleAnalyticsID', _t('GoogleAnalyticsExtension.GOOGLEANALYTICSID', 'Google Analytics ID')),
            ]
        );
        $fields->fieldByName("Root.GoogleAnalytics")->setTitle(_t('GoogleAnalyticsExtension.GOOGLEANALYTICSTAB', 'Google Analytics'));
    }

    public function updateSiteCMSFields(FieldList $fields): void
    {
        $this->updateCMSFields($fields);
    }
}
