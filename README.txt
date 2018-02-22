=== Woo Solo Api ===
Contributors: dingo_bastard
Tags: woocommerce, api, solo api, solo, api integration, shop, payment, woo
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 4.9.4
Stable tag: 1.2
WC requires at least: 3.0.0
WC tested up to: 3.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin provides integration of the SOLO service with WooCommerce store.

== Description ==

Woo Solo Api plugin will integrate with your checkout process, and will send the API request with
the order details to the SOLO service which will in turn create a PDF of the invoice or order on your SOLO dashboard.

Additionally, the invoice or order will be stored on your site, and you'll have the option of sending this PDF as a separate
mail to the client.

There is an entire options page where you can specify the details of the order or the invoice such as unit measure, payment
options, language of the invoice, currency and others.

The plugin is translated to Croatian, since the SOLO service is primarily used by Croatian users.

For more information about the SOLO API visit this link: https://solo.com.hr/api-dokumentacija

== Installation ==

= Install & Activate =

Installing the plugin is easy. Just follow these steps:

**Installing from WordPress repository:**

Be sure you have WooCommerce plugin installed first, otherwise you'll get an error on the plugin activation.

1. From the dashboard of your site, navigate to Plugins --> Add New.
2. In the Search type Woo Solo Api
3. Click Install Now
4. When it's finished, activate the plugin via the prompt. A message will show confirming activation was successful.

**Uploading the .zip file:**

1. From the dashboard of your site, navigate to Plugins --> Add New.
2. Select the Upload option and hit "Choose File."
3. When the popup appears select the woo-solo-api.x.x.zip file from your desktop. (The 'x.x' will change depending on the current version number).
4. Follow the on-screen instructions and wait as the upload completes.
5. When it's finished, activate the plugin via the prompt. A message will show confirming activation was successful.

= Requirements =

* PHP 5.6 or greater (recommended: PHP 7 or greater)
* WordPress 4.4 or above (because of the built in REST interface)
* jQuery 1.11.x

== Screenshots ==

1. After installation, you can go to the plugin settings and add your SOLO token
2. You can set up various options that are directly linked to how you want the invoice to look like
3. You can add some additional WooCommerce settings such as PIN and IBAN fields
4. You can add the mail settings that will be send (if you choose to) to the client when the order is completed

== Changelog ==

= 1.0 =
* Initial release

= 1.0.1 =
* Typo fix

= 1.1 =
* Added multiple invoice type selection based on payment type with appropriate fiscalization check
* Fiscalization will be disabled for offer by default, and enabled only on invoice type

= 1.2 =
* Renamed classes - shorten name of the classes and changed file names
* Fixed bug that prevented sending of the pdf bill if the type of the bill was offer (ponuda)

