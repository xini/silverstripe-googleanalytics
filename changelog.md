# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/).

## [5.1.2]

* fix config for fromholdio/silverstripe-configured-multisites

## [5.1.1]

* Fix Undefined variable $pageview error (thanks @portable-michael-nk)

## [5.1.0]

* add support for fromholdio/silverstripe-configured-multisites

## [5.0.0]

* upgrade to Silverstripe 5
* remove Universal Analytics support
* add GTag support
* remove v2 upgrade task

## [4.0.4]

* for multisites, use current instead of active site in frontend to prevent session creation

## [4.0.3]

* ensure get_analytics_config returns ActiveSite rather than Current for multisites

## [4.0.2]

* make GA settings optional
* fix template inclusion in readme

## [4.0.1]

* update license information
* update readme

## [4.0.0]

* upgraded for Silverstripe 4 compatibility
* removed option of Old Asynchronous Analytics
* updated upgrade service to handle migration from v1/2/3 to v4 (`Old Asynchronous Analytics` config settings migrated to `Universal Analytics`)

## [3.0.2]

* fix config to include cms js

## [3.0.1]

* fix upgrade service for multisites setups

## [3.0.0]

* change module to only use template setup: no auto injection into head any more because tag manager needs noscript within body, which cannot be injected using SS without modifying the base template.

