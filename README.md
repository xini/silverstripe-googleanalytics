# silverstripe-googleanalytics

## Overview

Adds Google Analytics to the site supporting:
* old Asynchronous Analytics (with event and download tracking) 
* Universal Analytics (with event and download tracking)
* Goggle Tag Manager.

The module is derived from [Shea's analytics module for multisites] (https://github.com/sheadawson/silverstripe-multisites-googleanalytics) and supports single site as well as [multisites] (https://github.com/silverstripe-australia/silverstripe-multisites) setups.

## Requirements

* SilverStripe ~3.1

## Installation

Install the module using composer:

```
composer require xini/silverstripe-googleanalytics dev-master
```

Add `<% include GoogleAnalyticsHead %>` in the `head` and `<% include GoogleAnalyticsBody %>` right after the opening `body` tag in your 
main page template.

Then run dev/build.

## Usage

All settings can be configured from the CMS.

In your SiteConfig (or the config of your Site when using multisites) you find a tab 'Google Analyitcs'. Select the analytics type you have setup for your GA account and add the ID. Done.

All GA code only gets inserted in live mode.

### Event tracking (old and Universal Analytics)

The module supports event tracking in the form of external, download, email and phone links.
 
You can enable event tracking on the SiteConfig (or Site for multisites) in the CMS.

#### Download tracking with custom controller urls (e.g. DMS module)

In order to track downloads that use a controller url instead of the direct file 
link (i.e. DMS module), please add the following attributes to the links:

```
class="download" data-extension="$Extension" data-filename="$FilenameWithoutID"
```

This will trigger the event tracking script to record the clicks.

#### Exclude links from tacking

To exclude links from being tracked add a class `do-not-track` to the anchor tag. 
This prevent the onclick event from firing. 