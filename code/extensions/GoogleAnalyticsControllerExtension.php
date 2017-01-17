<?php

class GoogleAnalyticsControllerExtension extends Extension
{
    
    public static function get_analytics_config() {
        if (class_exists('Multisites')) {
            return Multisites::inst()->getCurrentSite();
        } else {
            return SiteConfig::current_site_config();
        }
        return null;
    }
    
    
    public function AnalyticsConfig()
    {
        return self::get_analytics_config();
    }
    
    public function ShowGoogleAnalytics()
    {
        $config = self::get_analytics_config();
        if (
            Director::isLive() &&
            $config && $config->exists() &&
            $config->GoogleAnalyticsID &&
            strpos($_SERVER['REQUEST_URI'], '/admin') === false &&
            strpos($_SERVER['REQUEST_URI'], '/Security') === false &&
            strpos($_SERVER['REQUEST_URI'], '/dev') === false) {
            return true;
        }
        return false;
    }

    /**
     * Return a custom url for the GA page view. Can be overwritten for page types
     * that allow different views on the same URL, i.e. multi step forms.
     * Should return false if default url is to be used.
     * Can return the URL to be used as String or in an array with "URL" => "Page Title".
     * The page title is only submitted if Universal Analytics or Google Tag Manager are used.
     * @return string|array|boolean
     */
    public function getCustomPageViewUrl()
    {
        return false;
    }
    
    public function onAfterInit()
    {
        if ($this->ShowGoogleAnalytics()) {
            $config = self::get_analytics_config();
            
            if ($config && $config->exists()) {
            
                if ($config->GoogleAnalyticsType == "Universal Analytics") {
                    
                    //  universal analytics
    
                    if (!Config::inst()->get('GoogleAnalyticsControllerExtension', 'use_template')) {
                        
                        // cookie domain
                        $domain = 'auto';
                        if ($config->GoogleAnalyticsCookieDomain && strlen(trim($config->GoogleAnalyticsCookieDomain)) > 0) {
                            $domain = trim($config->GoogleAnalyticsCookieDomain);
                        }
                        
                        // page view url
                        $pageview = "ga('send', 'pageview');";
                        if ($urldata = $this->owner->getCustomPageViewUrl()) {
                            if (is_array($urldata)) {
                                $pageview = "";
                                // check if associative array
                                if (array_keys($urldata) !== range(0, count($urldata) - 1)) {
                                    foreach ($urldata as $url => $title) {
                                        $pageview .= "ga('send', { 'hitType': 'pageview', 'page': '$url', 'title': '$title' });";
                                    }
                                } else {
                                    foreach ($urldata as $url) {
                                        $pageview .= "ga('send', 'pageview', '$url');";
                                    }
                                }
                            } elseif (is_string($urldata)) {
                                $pageview = "ga('send', 'pageview', '$urldata');";
                            }
                        }
                        
                        // tracking code
                        Requirements::insertHeadTags("
    						<script type=\"text/javascript\">
    							(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    							(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    							m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    							})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    							ga('create', '".$config->GoogleAnalyticsID."', '".$domain."');
    							".$pageview."
    						</script>
    					", 'GA');
                    }
                    
                    // event tracking
                    if ($config->GoogleAnalyticsUseEventTracking) {
                        Requirements::javascript(THIRDPARTY_DIR.'/jquery/jquery.min.js');
                        Requirements::javascript(GOOGLEANALYTICS_DIR.'/javascript/event-tracking-universal.js');
                    }
                    
                } else if ($config->GoogleAnalyticsType == "Old Asynchronous Analytics") {
                    
                    // asynchronous analytics
    
                    if (!Config::inst()->get('GoogleAnalyticsControllerExtension', 'use_template')) {
                        
                        // cookie domain
                        $domain = '';
                        if ($config->GoogleAnalyticsCookieDomain && strlen(trim($config->GoogleAnalyticsCookieDomain)) > 0) {
                            $domain = "_gaq.push(['_setDomainName', '".trim($config->GoogleAnalyticsCookieDomain)."']);";
                        }
                        
                        // page view url
                        $pageview = "_gaq.push(['_trackPageview']);";
                        if ($urldata = $this->owner->getCustomPageViewUrl()) {
                            if (is_array($urldata)) {
                                $pageview = "";
                                // check if associative array
                                if (array_keys($urldata) !== range(0, count($urldata) - 1)) {
                                    foreach ($urldata as $url => $title) {
                                        $pageview .= "_gaq.push(['_trackPageview', '$url']);";
                                    }
                                } else {
                                    foreach ($urldata as $url) {
                                        $pageview .= "_gaq.push(['_trackPageview', '$url']);";
                                    }
                                }
                            } elseif (is_string($urldata)) {
                                $pageview = "_gaq.push(['_trackPageview', '$urldata']);";
                            }
                        }
                        
                        // tracking code
                        Requirements::insertHeadTags("
    						<script type=\"text/javascript\">
    							var _gaq = _gaq || [];
    							_gaq.push(['_setAccount', '".$config->GoogleAnalyticsID."']);
    							".$domain."
    							".$pageview."
    							(function() {
    								var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    								ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    								var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    							})();
    						</script>
    					", "GAasync");
                    }
                    
                    // event tracking
                    if ($config->GoogleAnalyticsUseEventTracking) {
                        Requirements::javascript(THIRDPARTY_DIR.'/jquery/jquery.min.js');
                        Requirements::javascript(GOOGLEANALYTICS_DIR.'/javascript/event-tracking.js');
                    }
                } else {
                    
                    // google tag manager
                    
                    if (!Config::inst()->get('GoogleAnalyticsControllerExtension', 'use_template')) {
                        $pageviews = array();
                        // virtual page view url
                        if ($urldata = $this->owner->getCustomPageViewUrl()) {
                            if (is_array($urldata)) {
                                // check if associative array
                                if (array_keys($urldata) !== range(0, count($urldata) - 1)) {
                                    foreach ($urldata as $url => $title) {
                                        $pageviews[] = array(
                                            'virtualPageURL' => $url,
                                            'virtualPageTitle' => $title,
                                        );
                                    }
                                } else {
                                    foreach ($urldata as $url) {
                                        $pageviews[] = array(
                                            'virtualPageURL' => $url,
                                        );
                                    }
                                }
                            } elseif (is_string($urldata)) {
                                $pageviews[] = array(
                                    'virtualPageURL' => $urldata,
                                );
                            }
                            if (count($pageviews)) {
                                $tag = "<script>dataLayer = [];"; 
                                foreach ($pageviews as $pageview) {
                                    $tag .= "dataLayer.push({'event': 'VirtualPageview','virtualPageURL': '".$pageview['virtualPageURL']."'";
                                    if (isset($pageview['virtualPageTitle'])) {
                                        $tag .= ",'virtualPageTitle': '".$pageview['virtualPageTitle']."'";
                                    }
                                    $tag .= "});";
                                }
        		                $tag .= "</script>";
                                Requirements::insertHeadTags($tag);
                            }
                        }
                        
                        // tracking code
                        Requirements::insertHeadTags("
    	<noscript><iframe src=\"//www.googletagmanager.com/ns.html?id=".$config->GoogleAnalyticsID."\"
    	height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
    	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    	})(window,document,'script','dataLayer','".$config->GoogleAnalyticsID."');</script>
    					", "GTM");
                    }
                }
            }
        }
    }
}
