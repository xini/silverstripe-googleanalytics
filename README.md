# silverstripe-googleanalytics

Adds Google Analytics to the site using the old Asynchronous Analytics and Universal Analytics with event and download tracking as well as Goggle Tag Manager.

The module is derived from [Shea's analytics module for multisites] (https://github.com/sheadawson/silverstripe-multisites-googleanalytics) and supports single site as well as [multisites] (https://github.com/silverstripe-australia/silverstripe-multisites) setups.

## Requirements

* SilverStripe 3.*

## Usage

The default behaviour inserts the tracking code automatically in the page head.

The code only gets inserted in live mode.

**Note:** If you are using Google Tag Manager it is recommended to add `<% include GoogleTagManagerScript %>` in 
the `head` and `<% include GoogleTagManagerNoScript %>` right after the opening `body` tag in your 
main page template. The automatic inclusion adds both parts in the head of the page (which is not correct for the noscript 
part) because at the moment there is no way of injecting something into the body without changing the template.

### Using a template for the tracking code

If you want to use the template version of the tracking code (i.e. if you need 
to modify the tracking code for your project/theme) add the following line to 
your config.yml:

```
GoogleAnalyticsControllerExtension:
  use_template: true
```

Use `<% include GoogleAnalytics %>` if you use GA or `<% include GoogleTagManagerScript %>` 
and `<% include GoogleTagManagerNoScript %>` if you use GTM in your layout template to insert the tracking code.

Copy the template `googleanalytics/templates/Includes/GoogleAnalytics.ss` or 
`googleanalytics/templates/Includes/GoogleTagManagerScript.ss` and `googleanalytics/templates/Includes/GoogleTagManagerNoScript.ss`
to your theme to make changes to the tracking code.

### Event tracking (old and Universal Analytics)

The module supports event tracking in the form of external, download, email and phone links.
 
You can enable event tracking on the SiteConfig (or Site for multisites) in the CMS.

#### Download tracking with custom controller urls (i.e. DMS module)

In order to track downloads that use a controller url instead of the direct file 
link (i.e. DMS module), please add the following attributes to the links:

```
class="download" data-extension="$Extension" data-filename="$FilenameWithoutID"
```

This will trigger the event tracking script to record the clicks.

#### Exclude links from tacking

To exclude links from being tracked add a class `do-not-track` to the anchor tag. 
This prevent the onclick event from firing. 