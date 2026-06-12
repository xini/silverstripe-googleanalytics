# silverstripe-googleanalytics

## Overview

Adds Google Analytics to the site supporting:
* Global Site Tag (gtag.js)
* Google Tag Manager (GTM)

The module is derived from [Shea's analytics module for multisites](https://github.com/sheadawson/silverstripe-multisites-googleanalytics) and supports single site, [multisites](https://github.com/symbiote/silverstripe-multisites) and [configured-multisites](https://github.com/fromholdio/silverstripe-configured-multisites) setups.

## Requirements

* Silverstripe 6

## Installation

Install the module using composer:

```
composer require innoweb/silverstripe-googleanalytics dev-master
```

Add `<% include Innoweb/GoogleAnalytics/GoogleAnalyticsHead %>` in the `head` and `<% include Innoweb/GoogleAnalytics/GoogleAnalyticsBody %>` right after the opening `body` tag in your
main page template.

Then run dev/build.

## Usage

All settings can be configured from the CMS.

In your SiteConfig (or the config of your Site when using multisites) you find a tab 'Google Analytics'. Select the analytics type you have setup for your GA account and add the ID. Done.

All GA code only gets inserted in live mode.

## License

BSD 3-Clause License, see [License](license.md)
