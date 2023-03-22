<% if $ShowGoogleAnalytics %>
	<% if $AnalyticsConfig.GoogleAnalyticsType == "Google Tag Manager" %>
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id={$AnalyticsConfig.GoogleAnalyticsID}"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<% end_if %>
<% end_if %>