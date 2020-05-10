# Changelog

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](https://semver.org/) and [Keep a CHANGELOG](https://keepachangelog.com/).

## [Unreleased]

### Added
### Changed
### Removed
### Fixed

_No documentation available about unreleased changes as of yet._

## [2.0.0] - TBA

### Added
### Changed
### Removed
### Fixed


## [1.9.6] - 2019-05-18

### Changed

- Tested with WordPress 5.2
- Tested with WooCommerce 3.6.3

## [1.9.5] - 2018-12-09

### Added

- Added escaping to activation messages

### Changed

- Updated `composer.json` (development only)
- Updated `phpcs.xml.dist` (development only)
- Tested with WordPress 5.0

### Fixed

- Fixed issue with multiple requests being made towards the SOLO service
- Fixed the issue with no emails being send on order status change
- Fixed the wrong plugin settings link on the plugins admin page
- Fixed `.travis` build (development only)
- Minor autoload fixes (removed manual requirement for activation hook)

## [1.9.4] - 2018-12-03

### Added

- Added removal of all offers

### Changed

- Changed the name of the offers and invoices

### Fixed

- Fixed important GDPR issue – no longer saving offers and invoices because they contain sensitive information, and google will crawl your site and make them public


## [1.9.3] - 2018-08-26

### Added

- Manually add translations for the recalculate note, since locale manipulation was causing site language change

### Fixed

- Fix the status check for the currency list

## [1.9.2] - 2018-07-11

### Fixed

- Minor fix – put WC() in the global scope

## [1.9.1] - 2018-06-30

### Fixed

- Minor fix in the Admin class constructor (string typehinting is not available prior to PHP 7)

## [1.9.0] - 2018-06-06

### Added

- Added the ability to translate the invoice/offer based on the selected and available language

### Changed

- Moved the Solo API options under the WooCommerce menu
- Extended the due date time to include 1-6 days in addition to 1-3 weeks

## [1.8.1] - 2018-06-06

### Added

- Add a check if no shipping is selected in the WooCommerce options

### Changed

- Minor code refactor (constants instead of properties in the main class)
- Replaced built in autoloader for composers autoloader

## [1.8.0] - 2018-05-07

### Added

- Add test method that returns all receipts from Solo API to check if the plugin is working properly.

### Changed

- Minor visual adjustments

## [1.7.5] - 2018-04-29

### Added

- Add fallback method for fetching currency rates in case the `allow_url_fopen is disabled.

## [1.7] - 2018-03-24

### Added

- Added payment types for each payment gateway

### Fixed

- Tax fixes – tax class wasn't working on changing the order to complete
- Added travis integration (development)
- Added code standards definition (development)

## [1.6] - 2018-03-23

### Fixed

- Fixed language issue on the invoices (props to ivoks)
- PHPCS fixes

## [1.5] - 2018-03-01

### Changed

- Set the transient validity to 6 hours so every 6 hours it will expire and be created again

### Fixed

- Fixed the currency rate to pull it from the official hnb site

## [1.4] - 2018-02-25

### Added

- Added automatic conversion rate from Croatian National Bank (https://www.hnb.hr/temeljne-funkcije/monetarna-politika/tecajna-lista/tecajna-lista)
- Add a fix for duplicated orders – you can now choose if you want to make SOLO API call on the checkout or when changing order status manually

### Fixed

- Fix for taxes rounding error on shipping

## [1.3] - 2018-02-25

### Added

- Add additional debug methods
- Add tax check – tax rate can be separate for shipping and for items, and are handled by WooCommerce

### Fixed

- Code sniffer fixes

## [1.2] - 2017-12-10

### Changed

- Renamed classes – shorten name of the classes and changed file names

### Fixed

- Fixed bug that prevented sending of the pdf bill if the type of the bill was offer (ponuda)

## [1.1] - 2017-11-23

### Added

- Added multiple invoice type selection based on payment type with appropriate fiscalization check

### Changed

- Fiscalization will be disabled for offer by default, and enabled only on invoice type

## [1.0.1] - 2017-11-22

### Fixed

- Typo fix

## [1.0] - 2017-11-22

- Initial release

[Unreleased]: https://github.com/dingo-d/woo-solo-api/compare/master...HEAD
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
