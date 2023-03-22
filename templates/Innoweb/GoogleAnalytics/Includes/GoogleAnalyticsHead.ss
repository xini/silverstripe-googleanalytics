<% if $ShowGoogleAnalytics %>
	<% if $AnalyticsConfig.GoogleAnalyticsType == "Global Site Tag" %>
		<script async src="https://www.googletagmanager.com/gtag/js?id={$AnalyticsConfig.GoogleAnalyticsID}"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '$AnalyticsConfig.GoogleAnalyticsID');
		</script>
		$PageViewUrlData
	<% else_if $AnalyticsConfig.GoogleAnalyticsType == "Google Tag Manager" %>
		$PageViewUrlData
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','{$AnalyticsConfig.GoogleAnalyticsID}');</script>
	<% end_if %>
<% end_if %>