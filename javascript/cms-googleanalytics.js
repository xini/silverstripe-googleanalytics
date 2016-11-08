(function($) {
	$('select#Form_EditForm_GoogleAnalyticsType').entwine({
		toggleState: function(){ 
			$('#Form_EditForm_GoogleAnalyticsCookieDomain_Holder').hide();
			$('#GAEventTracking').hide();
			if($(this).val() == 'Old Asynchronous Analytics' || $(this).val() == 'Universal Analytics')  {
				$('#Form_EditForm_GoogleAnalyticsCookieDomain_Holder').show();
				$('#GAEventTracking').show();
			}
		},
		onchange: function(){
			this.toggleState();
		},
		onmatch: function(){
			this.toggleState();
		}
	});
})(jQuery);