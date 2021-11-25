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

**Contributors**: dingo_bastard  
**Tags**: woocommerce, api, solo api, solo, api integration, shop, payment, woo  
**Requires at least**: 5.3  
**Requires PHP**: 7.3  
**Tested up to**: 5.6  
**Stable tag**: 2.0.8
**WC requires at least**: 4.0.0  
**WC tested up to**: 4.8.0  
**License**: MIT  
**License URI**: https://opensource.org/licenses/MIT  

This plugin provides integration of the SOLO service with WooCommerce store.

## Description

Woo Solo API plugin will integrate with your checkout process, and will send the API request with
the order details to the SOLO service which will in turn create a PDF of the invoice or order on your SOLO dashboard.

Additionally, the invoice or order will be stored on your site, and you'll have the option of sending this PDF as a separate
mail to the client.

There is an entire options page where you can specify the details of the order, or the invoice such as unit measure, payment
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

The project follows PSR-12 standards, and uses PSR-4 autoloading. To install the development dependencies you'll need to run:

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
npm run watch
```

There are a lot of composer scripts available for checking the code quality ([PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer/), [PHPStan](https://github.com/phpstan/phpstan)), and the code has tests which are built using [wp-browser](https://github.com/lucatume/wp-browser/).

### Running tests

To run tests, you'll need to set up [testing environment](https://wpbrowser.wptestkit.dev/getting-started/setting-up-minimum-wordpress-installation), and modify your `.env.testing`.
In order to run acceptance tests, you'll need to install selenium and chrome webdriver. You can read the official Codeception WebDriver [documentation](https://codeception.com/docs/modules/WebDriver#Selenium) on setting those up.

Once you set up everything, you can run the tests using Composer scripts:

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

## Acknowledgements 

The webpack config is partially taken from [Eightshift frontend libs](https://github.com/infinum/eightshift-frontend-libs/)

Settings page was created with the help of [tutorial](https://www.codeinwp.com/blog/plugin-options-page-gutenberg/) by Hardeep Asrani.

Parts of the architecture were influenced by [Alain Schlessera's Workshops](https://github.com/schlessera/wcbtn-2018-api).

## Frequently Asked Questions

### Why is the PHP 7.3 required?

Because the reality is that PHP 8 right outside the door. And if you have older PHP versions, you are not only doing a disservice to the planet, you're also harming your own site speed and security. You can always ask your hosting provider to fix this for you, or if you have control over cPanel on shared hosting, you are likely to be able to update PHP version yourself. Just make sure you tick all the required PHP extensions (https://make.wordpress.org/hosting/handbook/handbook/server-environment/#php-extensions).

### I didn't receive an invoice when testing the plugin. What's wrong?

Give it a minute, scroll your site. The API communication happens using WP cron, which depends on the site activity to work. You can always check the 'Solo API order details' tab in the settings to see if there was some error from the API.
If that fails, turn on the debug mode (https://wordpress.org/support/article/debugging-in-wordpress/) and try to test again. If there was a fatal error it should be shown in the logs.

### This plugin doesn't work with XYZ plugin

Could be. I didn't test it out with every plugin in the wild. That takes time. And money. And so far, nobody paid me to work on this plugin. In the near future I might be able to do some paid work, but that will depend on many things.
Please don't try to guilt-trip me to work on your site specific issues. I will try to help to the best of my abilities. But the plugin is free, and I am not doing paid support, so don't expect me to work miracles. Also, don't expect me to drop everything just to work on your issue. If I don't answer in a day or two, be patient. I have a private life as well (shocker, I know) :D

### Can I modify how the email looks, or customer notice?

Sure you can, besides actually adding things in the settings of the plugin you can modify certain things using these hooks:

Filters the custom message for customer note

If you need to extend the customer note, you can just hook to this filter
and modify the existing content

```php
@param string $customerNote Existing customer note.

apply_filters('woo_solo_api_modify_customer_note', $customerNote);
```

Adds a global discount

WooCommerce will handle counting the discounts for us.
This is why this is set to 0.
We can hook into this if we want to change it.
But this hook will affect every item. So use it with care.

```php
@param int $globalDiscount The value of the global discount to apply to every item.

apply_filters('woo_solo_api_add_global_discount', $globalDiscount ### 0
```

Filters the email message from the options

Email message can be set in the options and will be outputted here.
If, for whatever reason we want to modify it some more, we can do that here.

```php
@param string $emailMessage Email message from options to filter.

apply_filters('woo_solo_api_modify_options_email_message', $emailMessage);
```

Filters the default email message

If you don't set the message in the options, you can still filter the default one.

```php
@param string $defaultMessage Email message to filter.

apply_filters('woo_solo_api_modify_default_email_message', $defaultMessage);
```

Modify the email title from the options

Email title for the customer can be set in the options,
but you can modify it further with this filter.

```php
@param string $emailTitle Email title.

apply_filters('woo_solo_api_modify_options_email_title', $emailTitle);
```

Modify the default email title from the options

If you don't use the title from the options, you can use default one.
And modify it.

```php
@param string $emailTitle Email title.

apply_filters('woo_solo_api_modify_default_email_title', $defaultTitle);
```

Filter email headers

When email to customer is sent, maybe you want to add something more. In that
case you'll probably need to modify the headers sent with the email.
Default ones are

```php
[
	'MIME-Version: 1.0',
	'Content-Type: text/html',
];
```

You can add to that list.

```php
@param array $headers Email headers to pass to wp_mail.

apply_filters('woo_solo_api_email_headers', $headers);
```

Filter the from name set from the options

```php
@param string $name Name to change in the "From" field.

apply_filters('woo_solo_api_change_email_from_name', $name);
```

## License

Copyright ©2021 Denis Žoljom.
This plugin is free software, and may be redistributed under the terms specified in the LICENSE file.
