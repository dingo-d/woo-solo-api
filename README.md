# Woo Solo Api

**Contributors**: dingo_bastard  
**Tags**: woocommerce, api, solo api, solo, api integration, shop, payment, woo  
**Requires at least**: 5.1  
**Requires PHP**: 7.2  
**Tested up to**: 5.4  
**Stable tag**: 2.0
**WC requires at least**: 3.9.0  
**WC tested up to**: 4.0.0  
**License**: MIT  
**License URI**: https://opensource.org/licenses/MIT  

This plugin provides integration of the SOLO service with WooCommerce store.

## Description

Woo Solo Api plugin will integrate with your checkout process, and will send the API request with
the order details to the SOLO service which will in turn create a PDF of the invoice or order on your SOLO dashboard.

Additionally, the invoice or order will be stored on your site, and you'll have the option of sending this PDF as a separate
mail to the client.

There is an entire options page where you can specify the details of the order or the invoice such as unit measure, payment
options, language of the invoice, currency and others.

The plugin is translated to Croatian, since the SOLO service is primarily used by Croatian users.

For more information about the SOLO API visit this link: https://solo.com.hr/api-dokumentacija

### Requirements

* PHP 7.2 or greater
* WordPress 5.1 or above
* WooCommerce 3.9 or above

## Development

### Requirements

* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/en/)

The project is built following PSR-12 standards, and uses PSR-4 autoloading. To install the development dependencies you'll need to run

```bash
composer install
```

To bundle the scripts and styles you'll need to run

```bash
npm install
npm run build
```

During the development you can use watch mode

```bash
npm run watch
```

There are a lot of composer scripts available for checking the code quality ([PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/), [PHPStan](https://github.com/phpstan/phpstan)), and the code is covered with tests which are ran on [wp-browser](https://github.com/lucatume/wp-browser/).

### Running tests

To run tests, you'll need to set up [testing environment](https://wpbrowser.wptestkit.dev/getting-started/setting-up-minimum-wordpress-installation), and modify your `.env.testing`.
In order to run acceptance tests, you'll need to install selenium and chrome webdriver. You can read the official Codeception WebDriver [documentation](https://codeception.com/docs/modules/WebDriver#Selenium) on setting those up.

Once everything is set up for you, you can run the tests using Composer scripts:

```bash
composer test:acceptance
composer test:functional
composer test:integration
composer test:unit
```

for every test suite.

If you are adding any new functionality, be sure to write tests for it. 

## Changelog

Check the [Changelog.md](https://github.com/dingo-d/woo-solo-api/blob/master/CHANGELOG.md)

## License

Copyright ©2020 Denis Žoljom.
This plugin is free software, and may be redistributed under the terms specified in the LICENSE file.
