# silverstripe-googleanalytics

Adds Google Analytics to the site using old and Universal Analytics with event and download tracking for both.
It is derived from Shea's module for multisites (https://github.com/sheadawson/silverstripe-multisites-googleanalytics).

## Requirements

* SilverStripe 3.1.*

## Usage

The default behaviour inserts the tracking code automatically in the page head.

The code only gets inserted in live mode.

### Using a template for the tracking code

If you want to use the template version of the tracking code (i.e. if you need 
to modify the tracking code for your project/theme) add the following line to 
your _config.php:

```
MultisiteAnalyticsControllerExtension::$use_template = true;
```

Use `<% include GoogleAnalytics %>` in your layout template to insert the tracking code.

Copy the template `multisites-googleanalytics/templates/Includes/GoogleAnalytics.ss` 
to your theme to make changes to the tracking code.

### Event tracking

The module also supports event tracking as well as tracking of external, download, email and phone links.
 
You can enable event tracking in the site config of the CMS.

#### Download tracking with custom controller urls (i.e. DMS module)

In order to track downloads that use a controller url instead of the direct file 
link (i.e. DMS module), please add the following attributes to the links:

```
class="download" data-extension="$Extension" data-filename="$FilenameWithoutID"
```

This will trigger the event tracking script to record the clicks.

### Exclude links from tacking

To exclude links from being tracked add a class `do-not-track` to the anchor tag. 
This prevent the onclick event from firing. 