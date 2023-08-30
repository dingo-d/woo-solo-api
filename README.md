<p align="center">
  <img alt="Woo Solo API" src="https://repository-images.githubusercontent.com/110279928/a5ffbd00-29cc-11eb-92ac-f1f6524a5c69"/>
</p>

[![codecov](https://img.shields.io/codecov/c/github/dingo-d/woo-solo-api?style=for-the-badge&token=ijU4RyOGxL)](https://codecov.io/gh/dingo-d/woo-solo-api)
[![GitHub tag](https://img.shields.io/github/tag/dingo-d/woo-solo-api.svg?style=for-the-badge)](https://github.com/dingo-d/woo-solo-api)
[![GitHub stars](https://img.shields.io/github/stars/dingo-d/woo-solo-api.svg?style=for-the-badge&label=Stars)](https://github.com/dingo-d/woo-solo-api)
[![Required PHP Version](https://img.shields.io/wordpress/plugin/required-php/woo-solo-api?style=for-the-badge)](https://www.php.net/supported-versions.php)
[![Tested WP Version](https://img.shields.io/wordpress/plugin/tested/woo-solo-api?style=for-the-badge)](https://wordpress.org/download/releases/)
[![license](https://img.shields.io/github/license/dingo-d/woo-solo-api.svg?style=for-the-badge)](https://github.com/dingo-d/woo-solo-api)

# Woo Solo API

**Contributors**: dingo-d  
**Tags**: woocommerce, api, solo api, solo, api integration, shop, payment, woo  
**Requires at least**: 6.0  
**Requires PHP**: 7.4  
**Tested up to**: 6.3.1  
**Stable tag**: 3.2.0
**WC requires at least**: 7.0.0  
**WC tested up to**: 8.0.3  
**License**: MIT  
**License URI**: https://opensource.org/licenses/MIT

This plugin provides integration of the SOLO service with WooCommerce store.

## Description

Woo Solo API plugin will integrate with your checkout process, and will send the API request with
the order details to the SOLO service which will in turn create a PDF of the invoice or order on your SOLO dashboard.

Additionally, the invoice or order will be stored on your site, and you'll have the option of sending this PDF as a
separate
mail to the client.

There is an entire options page where you can specify the details of the order, or the invoice such as unit measure,
payment
options, language of the invoice, currency and others.

The plugin is translated to Croatian, since the SOLO service is primarily used by Croatian users.

For more information about the SOLO API visit this link: https://solo.com.hr/api-dokumentacija

### Requirements

* PHP 7.4 or greater
* WordPress 6.0 or above
* WooCommerce 7.0 or above

## Development

### Requirements

* [Composer](https://getcomposer.org/)
* [Node.js](https://nodejs.org/en/)

The project follows PSR-12 standards, and uses PSR-4 autoload. To install the development dependencies you'll need to
run:

```bash
composer install
```

To bundle the scripts and styles you'll need to run:

```bash
npm install
npm run build
```

During the development you can use watch mode:

```bash
npm run dev
```

There are a lot of composer scripts available for checking the code
quality ([PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/), [PHPStan](https://github.com/phpstan/phpstan)),
and the code has tests which are built using [wp-pest](https://github.com/dingo-d/wp-pest/).

### Running tests

In order to run the tests you'll first need to set up the test suite:

```bash
vendor/bin/wp-pest setup plugin --plugin-slug=woo-solo-api --skip-delete
```

Then you can run the tests using

```bash
composer test:unit
composer test:integration
```

### Run all code quality checks

Running all php code quality checks you can run

```bash
composer check-all
```

And to run all the JS/SCSS checks you can run

```bash
npm run lint
```

## Changelog

Check the [Changelog.md](https://github.com/dingo-d/woo-solo-api/blob/master/CHANGELOG.md)

## Acknowledgements

The autowiring config is taken from [Eightshift libs](https://github.com/infinum/eightshift-libs/)

Parts of the architecture were influenced
by [Alain Schlessera's Workshops](https://github.com/schlessera/wcbtn-2018-api).

## Frequently Asked Questions

### Why is the PHP 7.4 required?

PHP 7.4 is in the [end of life](https://www.php.net/supported-versions.php) phase. You should really move on to PHP 8.

If you have older PHP versions, you are not only doing a disservice to the planet, you're also harming your own site speed and security. You can always ask your hosting provider to fix this for you, or if you have control over cPanel on shared hosting, you are likely to be able to update PHP version yourself. Just make sure you tick all the required PHP
extensions (https://make.wordpress.org/hosting/handbook/handbook/server-environment/#php-extensions).

### I didn't receive an invoice when testing the plugin. What's wrong?

Give it a minute, scroll your site. The API communication happens using WP cron, which depends on the site activity to
work. You can always check the 'Solo API order details' tab in the settings to see if there was some error from the API.
If that fails, turn on the debug mode (https://wordpress.org/support/article/debugging-in-wordpress/) and try to test
again. If there was a fatal error it should be shown in the logs.

In the version 3 of the plugin, for every failed request you'll be able to see the error and try to rerun the call towards the API.

### This plugin doesn't work with XYZ plugin

Could be. I didn't test it out with every plugin in the wild. That takes time. And money. In the near future I might be able to do some paid work, but that will depend on many things.

Please don't try to guilt-trip me to work on your site specific issues. I will try to help to the best of my abilities.
But the plugin is free, so don't expect me to work miracles. Also, don't expect me to
drop everything just to work on your issue. If I don't answer in a day or two, be patient. I have a private life as
well (shocker, I know) :D

If you want me to add a new feature to the plugin you can contact me and pay me to work on the feature you need. My rate is 50€/hr.

Note that some features are not possible to make within this plugin. For instance, some apps and plugins will utilize WooCommerce REST API endpoints to trigger orders. Their API is poorly written, and when you do these kind of actions, the hooks that will look if something changed won't get triggered, which means this plugin cannot know of the order status. It is what it is. I could make some kind of listener/cron jobs to manually check these things, but this is a paid feature.

### Can I modify how the email looks, or customer notice?

You can modify the request towards the Solo service using hooks described in the project's [wiki page](https://github.com/dingo-d/woo-solo-api/wiki/Plugin-filters).

## License

Copyright ©2023 Denis Žoljom.
This plugin is free software, and may be redistributed under the terms specified in the LICENSE file.
