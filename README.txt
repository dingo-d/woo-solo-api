=== Woo Solo Api ===
Contributors: dingo_bastard
Tags: woocommerce, api, solo api, solo, api integration, shop, payment, woo
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 4.9.4
Stable tag: 1.4
WC requires at least: 3.0.0
WC tested up to: 3.3.3
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

= 1.4 =
* Added automatic conversion rate from Croatian National Bank (https://www.hnb.hr/temeljne-funkcije/monetarna-politika/tecajna-lista/tecajna-lista)
* Add a fix for duplicated orders - you can now choose if you want to make SOLO API call on the checkout or when changing order status manually
* Fix for taxes rounding error on shipping

= 1.3 =
* Add additional debug methods
* Code sniffer fixes
* Add tax check - tax rate can be separate for shipping and for items, and are handled by WooCommerce

= 1.2 =

* Renamed classes - shorten name of the classes and changed file names
* Fixed bug that prevented sending of the pdf bill if the type of the bill was offer (ponuda)

= 1.1 =

* Added multiple invoice type selection based on payment type with appropriate fiscalization check
* Fiscalization will be disabled for offer by default, and enabled only on invoice type

= 1.0.1 =

* Typo fix

= 1.0 =

* Initial release

== Additional notes ==

The 1.4 update fixed a lot of issues with the taxes, and tax rates. Although this has been fixed, the taxes probably wont't be picked up by the SOLO API if you set the option in the WooCommerce to include taxes in the price of the product. This is why you should separate the two. Also when creating a shipping tax class, be sure to create it as aseparate class, and leave the standard tax class for the product. So for instance the product will have a standard tax of 5%, and the shipping can have a separate class with tax rate of 25% (be sure to tick the shipping checkbox in this class).
The including prices will be fixed in the next update.

----

The 1.3 update removed the predefined tax rates from the plugin, and is instead using WooCommerce taxes.
This means that you can have different tax for the item, and different for the shipping. Word of caution though, the Croatian law has predefined taxes of 0, 5, 13 or 25%. So in case you put the tax for the item and shipping anything other than that, the tax rate will be sent as 0 - so that your SOLO invoice or offer will pass, but with 0 tax rate.

Also be sure to separate those two taxes. For instance you can set the item tax rate as a standard tax rate, and then the shipping tax can be set as a reduced tax rate, and then just set the Shipping tax class to reduced tax rate in the WooCommerce options. If you put everything as a standard one, the tax rates will get mixed and the end price won't be correct (not sure how else to handle this since WooCommerce has a weird tax system).
