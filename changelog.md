# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/).

## [4.0.0]

* upgraded for SilverStripe 4 compatibility
* removed option of Old Asynchronous Analytics
* updated upgrade service to handle migration from v1/2/3 to v4 (`Old Asynchronous Analytics` config settings migrated to `Universal Analytics`)

## [3.0.2]

* fix config to include cms js

## [3.0.1]

* fix upgrade service for multisites setups

## [3.0.0]

* change module to only use template setup: no auto injection into head any more because tag manager needs noscript within body, which cannot be injected using SS without modifying the base template.

