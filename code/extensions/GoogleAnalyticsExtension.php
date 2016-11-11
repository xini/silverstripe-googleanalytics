<?php
class GoogleAnalyticsExtension extends DataExtension
{
    
    private static $db = array(
        'GoogleAnalyticsType' => "Enum(',Old Asynchronous Analytics,Universal Analytics,Google Tag Manager', '')",
        'GoogleAnalyticsID' => 'Varchar',
        'GoogleAnalyticsCookieDomain' => 'Varchar(255)',
        'GoogleAnalyticsUseEventTracking' => 'Boolean',
        
        // legacy and upgrading fields
        'GoogleAnalyticsUseUniversalAnalytics' => 'Boolean',
        'GoogleAnalyticsUpgradedV2' => 'Boolean',
    );
    
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.GoogleAnalytics',
            array(
                DropdownField::create(
                    "GoogleAnalyticsType",
                    _t('GoogleAnalyticsExtension.GoogleAnalyticsType', 'Google Analytics Type'),
                    Singleton('SiteConfig')->dbObject('GoogleAnalyticsType')->enumValues()
                )->setRightTitle(
                    _t(
                        'GoogleAnalyticsExtension.TypeHelp',
                        "Please select the correct Analytics type according to the setup of your Google Analytics account."
                    )
                ),
                
                TextField::create('GoogleAnalyticsID', _t('GoogleAnalyticsExtension.GOOGLEANALYTICSID', 'Google Analytics ID')),
                    
                TextField::create(
                    'GoogleAnalyticsCookieDomain',
                    _t('GoogleAnalyticsExtension.NONSTANDARDCOOKIEDOMAIN', 'Non-Standard Cookie Domain')
                )
                ->setRightTitle(
                    _t(
                        'GoogleAnalyticsExtension.COOKIEDOMAINHELP',
                        "If you want to use a non-standard cookie domain for your tracking, please enter it here. If this field is left empty, 'auto' will be used."
                    )
                ),
                
                FieldGroup::create(
                    CheckboxField::create('GoogleAnalyticsUseEventTracking', '')
                )
                ->setTitle(_t('GoogleAnalyticsExtension.USEEVENTTRACKING', 'Use Event Tracking'))
                ->setName('GAEventTracking')
                ->setRightTitle(
                    _t(
                        'GoogleAnalyticsExtension.EVENTTRACKINGHELP',
                        "Activate this box if you want to track events like downloads and clicks on external, email and phone links. "
                    )
                ),
            )
        );
        $fields->fieldByName("Root.GoogleAnalytics")->setTitle(_t('GoogleAnalyticsExtension.GOOGLEANALYTICSTAB', 'Google Analytics'));
    }
    
    public function requireDefaultRecords() {
    
        // Perform migrations
        Injector::inst()
        ->create('GoogleAnalyticsUpgradeService')
        ->setQuiet(true)
        ->run();
    
        DB::alteration_message('Migrated googleanalytics', 'changed');
    }
    
}
