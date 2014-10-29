<?php
class GoogleAnalyticsExtension extends DataExtension {
	
	private static $db = array(
		'GoogleAnalyticsID' => 'Varchar',
		'GoogleAnalyticsUseUniversalAnalytics' => 'Boolean',
		'GoogleAnalyticsCookieDomain' => 'Varchar(255)',
		'GoogleAnalyticsUseEventTracking' => 'Boolean',
	);
	
	private static $defaults = array(
		'GoogleAnalyticsUseUniversalAnalytics' => true,
	);

	public function updateCMSFields(FieldList $fields){
		$fields->addFieldsToTab(
			'Root.GoogleAnalytics', 
			array(
				TextField::create('GoogleAnalyticsID', _t('GoogleAnalyticsExtension.GOOGLEANALYTICSID', 'Google Analytics ID')),
					
				FieldGroup::create(
					CheckboxField::create('GoogleAnalyticsUseUniversalAnalytics', '')
				)
				->setTitle(_t('GoogleAnalyticsExtension.USEUNIVERSALANALYTICS', 'Use Universal Analytics'))
				->setName('GAUniversalAnalytics')
				->setRightTitle(
					_t(
						'GoogleAnalyticsExtension.UNIVERSALANALYTICSHELP', 
						"Universal Analytics is the new analytics implementation from Google. If your Google Analytics account is set up to use Universal Analytics, please check this box."
					)
				),
					
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
}
