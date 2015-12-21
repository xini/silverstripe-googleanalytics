(function($) {

	$(document).ready(function() {
	
		var filetypes = /\.(zip|exe|dmg|pdf|doc.*|xls.*|ppt.*|mp3|txt|rar|wma|mov|avi|wmv|flv|wav)$/i;
		var TLDPrimary = /(com|org|net|int|edu|gov|mil|vic|nsw|qld)$/i;
		var baseHref = '';
		if ($('base').attr('href') != undefined) baseHref = $('base').attr('href');
		var hrefRedirect = '';
	 
		$('body').on('click', 'a', function(event) {
			var el = $(this);
			var track = true;
			var href = (typeof(el.attr('href')) != 'undefined' ) ? el.attr('href') : '';
			var reverseDomain = document.domain.split('.').reverse();
			var domainBase = 0;

			for(i = 0; i < reverseDomain.length; i++) {
				if(reverseDomain[i].length >= 3 && !reverseDomain[i].match(TLDPrimary)) {
					domainBase = i;
					break;
				}
			}

			if(domainBase == 0 && reverseDomain[0] != 'localhost')
				domainBase = 1;

			var siteBase = '';

			for(i = 0; i <= domainBase; i++) {
				if(siteBase == '')
					siteBase = reverseDomain[i] + siteBase;
				else
					siteBase = reverseDomain[i] + '.' + siteBase;
			}
			siteBase += '/';
			
			var isThisDomain = false;

			if(href.match(/(http)$/i))
				isThisDomain = href.match(siteBase);
			else if (href.indexOf('/') === 0)
				isThisDomain = true;
			
			if (!href.match(/^javascript:/i)) {
				var elEv = []; elEv.value=0, elEv.non_i=false;
				if (href.match(/^mailto\:/i)) {
					elEv.category = "email";
					elEv.action = "click";
					elEv.label = href.replace(/^mailto\:/i, '');
					elEv.loc = href;
				}
				else if (el.hasClass('download')) { 
					// extra for dms module. 
					// download links need to have the following attributes:
					// class="download" data-extension="$Extension" data-filename="$FilenameWithoutID"
					elEv.category = 'download';
					elEv.action = 'click-' + el.data('extension');
					elEv.label = el.data('filename');
					elEv.loc = baseHref + href;
				}
				else if (href.match(/^https?\:/i) && !isThisDomain) {
					elEv.category = "external";
					elEv.action = "click";
					elEv.label = href.replace(/^https?\:\/\//i, '');
					elEv.non_i = true;
					elEv.loc = href;
				}
				else if (href.match(filetypes)) {
					var extension = (/[.]/.exec(href)) ? /[^.]+$/.exec(href) : undefined;
					elEv.category = "download";
					elEv.action = "click-" + extension[0];
					elEv.label = href.replace(/ /g,"-");
					elEv.loc = baseHref + href;
				}
				else if (href.match(/^tel\:/i)) {
					elEv.category = "telephone";
					elEv.action = "click";
					elEv.label = href.replace(/^tel\:/i, '');
					elEv.loc = href;
				}
				else track = false;
		 
				if (track) {
					//alert(elEv.category.toLowerCase()+" - "+elEv.action.toLowerCase()+" - "+elEv.label.toLowerCase());
					_gaq.push(['_trackEvent', elEv.category.toLowerCase(), elEv.action.toLowerCase(), elEv.label.toLowerCase(), elEv.value, elEv.non_i]);
					if ( el.attr('target') == undefined || el.attr('target').toLowerCase() != '_blank') {
						setTimeout(function() { location.href = elEv.loc; }, 400);
						return false;
					}
				}
			}
		});
		
	});
	
}(jQuery));