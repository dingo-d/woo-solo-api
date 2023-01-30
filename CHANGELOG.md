# Changelog

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](https://semver.org/) and [Keep a CHANGELOG](https://keepachangelog.com/).

## [Unreleased]

### Added

### Changed

### Removed

### Fixed

_No documentation available about unreleased changes as of yet._

## [3.0.0] - 2023-01-30

This update was mostly a dev update to make the plugin easier to maintain.
A lot was changed under the hood (from handling dependency injection to build process, and how settings are handled).
Minor issues in the plugin should be fixed, most notable is that the translation for Croatian language should work now.
Also, the plugin should work with PHP 8+ (beta compatibility). PHP 8.2 could throw some notices, but WP is not yet fully compatible with PHP 8, so you can report any issues you find.

### Added

* Improved handling of dependencies in files
* Method that will delete HNB exchange rate transient on plugin update
* Improved error handling on API call failures

### Changed

* Minimum PHP version updated to 7.4, beta compatibility with PHP 8+ added 
* Build process changed from Webpack to Vite 
* Updated the options page a bit (better organization)
  * The settings are a bit more fool-proof now in the way settings are fetched, stored and handled across the admin app.

### Fixed

* Translations for Croatian language should work now
* If people didn't update their plugin or settings, the currency should automatically default to EUR
* All outstanding coding style and type errors
* Better exception descriptions

## [2.3.0] - 2023-01-04

This release will fix the affected change due to Croatia entering the Euro-zone.

### Added

* Add a fallback to checking if offers exist in case the invoices don't exist
* Add a way to clear Croatian National Bank API transient response

### Changed

* Update HNB API link to a new v3 working one
* Fix conversion logic for currencies

### Fixed

* Fix filter type for plugin link hook

## [2.2.0] - 2022-01-18

### Added

* Added new filters (See FAQ section for more info)

### Fixed

* Fix the localization notice
* Fix PHPStan errors

## [2.1.0] - 2021-03-07

### Added

* Added new filters and arguments to existing filters (See FAQ section for more info)
* Added a check if WooCommerce plugin is active to prevent fatal errors if you disable WooCommerce before this plugin

### Fixed

* Change name of the language translation files so that they work properly
  * In case the translation doesn't work automatically, place the woo-solo-api-hr.mo in the
    /wp-content/languages/plugins folder

## [2.0.8] - 2020-12-29

### Changed

* Updated JS packages and webpack bundling process (updated to webpack 5)

### Fixed

* Update bundle of the assets and the PHP packages (possible cause to some issues in version 2.0.7?)

## [2.0.7] - 2020-12-28

### Fixed

* Changed the typehint of the admin order fields hook.

## [2.0.6] - 2020-11-30

### Fixed

* Add assets to the plugin. The GH Action tag is not working as intended so this is a manual fix.

## [2.0.5] - 2020-11-29

### Fixed

* Fix the company name showing on the order and sending taxes option towards API.

## [2.0.4] - 2020-11-28

### Added

* Clear error log message if order is manually triggered and succeeds.

### Changed

* Priority changed on the on completed WooCommerce hook.

### Fixed

* Remove shipping from all the variables, fix shipping tax calculation
  *The shipping does not concern us really, so no need to pull any fields from there.
  * Shipping was a bit changed, so it didn't pick anything up and reported 0 tax on taxable shipping.
* Fix the defaults in the settings (languages, checkout settings) in PHP.

## [2.0.3] - 2020-11-25

### Fixed

* Add a default fallback on save in case a settings is undefined or null in settings

## [2.0.2] - 2020-11-24

### Fixed

* Add a check if the options to be serialized exist at all before attempting to deserialize them

## [2.0.1] - 2020-11-23

### Fixed

* Autoload order in main plugin file, so that the first error can be thrown correctly, if it happens
* Fix the uninstallation error because of a missing autoloader file
* Add fallbacks in case there is no gateway set, so that the JS doesn't fail when deserializing arrays

## [2.0.0] - 2020-11-22

This is a very big update. I called this the refactor, because the basic functionality of the plugin remained the
same. But in all reality, this was a total plugin rewrite. You'll need to make sure you have PHP 7.3 installed on your
server.

The options remained the same, so none of the settings on an already existing version should disappear. Plugin will
need to be disabled and then enabled again once updated, so that the database table can be created.

There is one bug that is coming from the fact that the new settings page is built using Gutenberg blocks. Due to a
nature of translations and how they work in GB, and the fact that I'm relying on webpack to bundle my assets. The
translations are not working properly. There is an issue opened in WP-CLI ([make-json does not work for .jsx files
](https://github.com/wp-cli/i18n-command/issues/200)), but so far we don't have a fix for this. Once the fix comes
out I'll update the plugin's translation files. The plugin's .po file is fully translated to Croatian, but we need a
json file as well for the JavaScript translations.

The options page has been revamped and is built using Gutenberg components. So that's nice.

Api calls towards Solo service are now run as a background process. So don't be alarmed if the order doesn't appear
immediately, or if the Solo invoice/order doesn't come immediately to the customer. Give it some time, and rummage
around a bit, to trigger WordPress cron :D

A lot of issues were fixed. The plugin should be more stable and work better now.

### Added

* Tests - several acceptance tests and integration tests using wp-browser
* Added several rest routes for internal use
  * Account Details - fetches orders from SOLO API (for testing if api calls work)
  * Order Details and its collection - details about orders fetched from the database
* Added new database for sending orders to API
  * We need to keep track if the order was sent or not, and it's better to have a table with this information in it. We
    can update it when the API request is made, and when the process for triggering user email happens. This gives us
    more control later on if we need to debug something (missing emails, missing requests, etc.).
* Add privacy related functionality
  * This functionality hooks into WP's own core privacy functionality. So that if the user requests their data to be
    either exported or deleted we remove the data in the custom table we created.
* Added filters for modifying certain data in the plugin
* Link the invoice from the Solo service with the WooCommerce invoice

### Changed

* Refactored the entire plugin structure to use dependency injection, autowiring, PSR-4 autoloading and PSR-12 coding
  standards
* Replaced old settings page with new, core editor powered settings page
* Settings page now includes WooCommerce navbar
* Sending any remote call towards Solo API is now done utilizing WordPress cron.
  * It's not ideal, as Wordpress cron is
    not a real cron, but it seems to be working. With queueing calls we are less likely to trigger API throttling from
    the Solo Api. Oftentimes there would be no orders created in the Solo service, and the user would be none the wiser
    what happened. By queueing we avoid this throttling. Also, we have a better way of logging errors from the API.

### Removed

* Removed the filter for PDF file types as those are no longer saved in the media uploads folder

### Fixed

* If 2 transactions are marked as complete, only one will send the call to solo API for completion
  * Fixed by adding a db with checks from the database
* When changing the order from on hold to completed in the order screen the API call is made again
  * Fixed by adding a db with checks from the database
* Make company field mandatory if R1 type of invoice is selected
* Check if the offer/bill is created twice
  * Fixed by adding a db with checks from the database
* Plugin recognizes Braintree and Corvus payment gateways

## [1.9.6] - 2019-05-18

### Changed

* Tested with WordPress 5.2
* Tested with WooCommerce 3.6.3

## [1.9.5] - 2018-12-09

### Added

* Added escaping to activation messages

### Changed

* Updated `composer.json` (development only)
* Updated `phpcs.xml.dist` (development only)
* Tested with WordPress 5.0

### Fixed

* Fixed issue with multiple requests being made towards the SOLO service
* Fixed the issue with no emails being send on order status change
* Fixed the wrong plugin settings link on the plugins admin page
* Fixed `.travis` build (development only)
* Minor autoload fixes (removed manual requirement for activation hook)

## [1.9.4] - 2018-12-03

### Added

* Added removal of all offers

### Changed

* Changed the name of the offers and invoices

### Fixed

* Fixed important GDPR issue – no longer saving offers and invoices because they contain sensitive information, and
  google will crawl your site and make them public

## [1.9.3] - 2018-08-26

### Added

* Manually add translations for the recalculated note, since locale manipulation was causing site language change

### Fixed

* Fix the status check for the currency list

## [1.9.2] - 2018-07-11

### Fixed

* Minor fix – put WC() in the global scope

## [1.9.1] - 2018-06-30

### Fixed

* Minor fix in the Admin class constructor (string typehinting is not available prior to PHP 7)

## [1.9.0] - 2018-06-06

### Added

* Added the ability to translate the invoice/offer based on the selected and available language

### Changed

* Moved the Solo API options under the WooCommerce menu
* Extended the due date time to include 1-6 days in addition to 1-3 weeks

## [1.8.1] - 2018-06-06

### Added

* Add a check if no shipping is selected in the WooCommerce options

### Changed

* Minor code refactor (constants instead of properties in the main class)
* Replaced built in autoloader for composers autoloader

## [1.8.0] - 2018-05-07

### Added

* Add test method that returns all receipts from Solo API to check if the plugin is working properly.

### Changed

* Minor visual adjustments

## [1.7.5] - 2018-04-29

### Added

* Add fallback method for fetching currency rates in case the `allow_url_fopen is disabled.

## [1.7] - 2018-03-24

### Added

* Added payment types for each payment gateway

### Fixed

* Tax fixes – tax class wasn't working on changing the order to status 'complete'
* Added travis integration (development)
* Added code standards definition (development)

## [1.6] - 2018-03-23

### Fixed

* Fixed language issue on the invoices (props to ivoks)
* PHPCS fixes

## [1.5] - 2018-03-01

### Changed

* Set the transient validity to 6 hours so every 6 hours it will expire and be created again

### Fixed

* Fixed the currency rate to pull it from the official hnb site

## [1.4] - 2018-02-25

### Added

* Added automatic conversion rate from Croatian National
  Bank (https://www.hnb.hr/temeljne-funkcije/monetarna-politika/tecajna-lista/tecajna-lista)
* Add a fix for duplicated orders – you can now choose if you want to make SOLO API call on the checkout or when
  changing order status manually

### Fixed

* Fix for taxes rounding error on shipping

## [1.3] - 2018-02-25

### Added

* Add additional debug methods
* Add tax check – tax rate can be separate for shipping and for items, and are handled by WooCommerce

### Fixed

* Code sniffer fixes

## [1.2] - 2017-12-10

### Changed

* Renamed classes – shorten the name of the classes and changed file names

### Fixed

* Fixed a bug that prevented sending of the pdf bill if the type of the bill was offer (ponuda)

## [1.1] - 2017-11-23

### Added

* Added multiple invoice type selection based on payment type with appropriate fiscalization check

### Changed

* Fiscalization will be disabled for offer by default, and enabled only on invoice type

## [1.0.1] - 2017-11-22

### Fixed

* Typo fix

## [1.0] - 2017-11-22

* Initial release

[Unreleased]: https://github.com/dingo-d/woo-solo-api/compare/master...HEAD
[3.0.0]: https://github.com/dingo-d/woo-solo-api/compare/2.3.0...3.0.0  
[2.3.0]: https://github.com/dingo-d/woo-solo-api/compare/2.2.0...2.3.0  
[2.2.0]: https://github.com/dingo-d/woo-solo-api/compare/2.1.0...2.2.0  
[2.1.0]: https://github.com/dingo-d/woo-solo-api/compare/2.0.8...2.1.0  
[2.0.8]: https://github.com/dingo-d/woo-solo-api/compare/2.0.7...2.0.8  
[2.0.7]: https://github.com/dingo-d/woo-solo-api/compare/2.0.6...2.0.7  
[2.0.6]: https://github.com/dingo-d/woo-solo-api/compare/2.0.5...2.0.6  
[2.0.5]: https://github.com/dingo-d/woo-solo-api/compare/2.0.4...2.0.5  
[2.0.4]: https://github.com/dingo-d/woo-solo-api/compare/2.0.3...2.0.4  
[2.0.3]: https://github.com/dingo-d/woo-solo-api/compare/2.0.2...2.0.3  
[2.0.2]: https://github.com/dingo-d/woo-solo-api/compare/2.0.1...2.0.2  
[2.0.1]: https://github.com/dingo-d/woo-solo-api/compare/2.0.0...2.0.1  
[2.0.0]: https://github.com/dingo-d/woo-solo-api/compare/1.9.6...2.0.0  
[1.9.6]: https://github.com/dingo-d/woo-solo-api/compare/1.9.5...1.9.6  
[1.9.5]: https://github.com/dingo-d/woo-solo-api/compare/1.9.4...1.9.5  
[1.9.4]: https://github.com/dingo-d/woo-solo-api/compare/1.9.3...1.9.4  
[1.9.3]: https://github.com/dingo-d/woo-solo-api/compare/1.9.2...1.9.3  
[1.9.2]: https://github.com/dingo-d/woo-solo-api/compare/1.9.1...1.9.2  
[1.9.1]: https://github.com/dingo-d/woo-solo-api/compare/1.9.0...1.9.1  
[1.9.0]: https://github.com/dingo-d/woo-solo-api/compare/1.8.1...1.9.0  
[1.8.1]: https://github.com/dingo-d/woo-solo-api/compare/1.8.0...1.8.1  
[1.8.0]: https://github.com/dingo-d/woo-solo-api/compare/1.7.5...1.8.0  
[1.7.5]: https://github.com/dingo-d/woo-solo-api/compare/1.7...1.7.5  
[1.7]: https://github.com/dingo-d/woo-solo-api/compare/1.6...1.7  
[1.6]: https://github.com/dingo-d/woo-solo-api/compare/1.5...1.6  
[1.5]: https://github.com/dingo-d/woo-solo-api/compare/1.4...1.5  
[1.4]: https://github.com/dingo-d/woo-solo-api/compare/1.3...1.4  
[1.3]: https://github.com/dingo-d/woo-solo-api/compare/1.2...1.3  
[1.2]: https://github.com/dingo-d/woo-solo-api/compare/1.1...1.2  
[1.1]: https://github.com/dingo-d/woo-solo-api/compare/1.0.1...1.1  
[1.0.1]: https://github.com/dingo-d/woo-solo-api/compare/1.1...1.0.1  
[1.0]: https://github.com/dingo-d/woo-solo-api/compare/24cff4a3cc72cfda688a01122ad86bb66e85c2a8...1.0  
