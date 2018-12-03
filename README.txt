=== Woo Solo Api ===
Contributors: dingo_bastard
Tags: woocommerce, api, solo api, solo, api integration, shop, payment, woo
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 4.9.6
Stable tag: 1.9.4
WC requires at least: 3.0.0
WC tested up to: 3.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

This plugin provides integration of the SOLO service with WooCommerce store.

== Description ==

Woo Solo Api plugin will integrate with your checkout process, and will send the API request with
the order details to the SOLO service which will in turn create a PDF of the invoice or order on your SOLO dashboard.

Additionally, the invoice or order will be stored on your site, and you'll have the option of sending this PDF as a separate
mail to the client.

There is an entire options page where you can specify the details of the order or the invoice such as unit measure, payment
options, language of the invoice, currency and others.

The plugin is translated to Croatian, since the SOLO service is primarily used by Croatian users. You can contribute and add additional languages.

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

= 1.9.4 =

* Changed the name of the offers and invoices
* Fixed important GDPR issue - no longer saving offers and invoices becuase they contain sensitive information, and google will crawl your site and make them public
* Added removal of all offers

= 1.9.3 =

* Fix the status check for the currency list
* Manually add translations for the recalculate note, since locale manipulation was causing site language change

= 1.9.2 =

* Minor fix - put WC() in the global scope

= 1.9.1 =

* Minor fix in the Admin class constructor (string typehinting is not available prior to PHP 7)

= 1.9.0 =

* Moved the Solo API options under the WooCommerce menu
* Added the ability to translate the invoice/offer based on the selected and available language
* Extended the due date time to include 1-6 days in addition to 1-3 weeks

= 1.8.1 =

* Add a check if no shipping is selected in the WooCommerce options
* Minor code refactor (constants instead of properties in the main class)
* Replaced built in autoloader for composers autoloader

= 1.8.0 =

* Add test method that returns all receipts from Solo API to check if the plugin is working properly.
* Minor visual adjustements

= 1.7.5 =

* Add fallback method for fetching currency rates in case the allow_url_fopen is disabled.

= 1.7 =

* Added payment types for each payment gateway
* Tax fixes - tax class wasn't working on changing the order to complete
* Added travis integration (development)
* Added code standards definition (development)

= 1.6 =

* Fixed language issue on the invoices (props to [ivoks](https://github.com/ivoks))
* phpcs fixes

= 1.5 =

* Fixed the currency rate to pull it from the official hnb site
* Set the transient validity to 6 hours so every 6 hours it will expire and be created again

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

=== ⚠️ Important update notice ⚠️ ===

Pre 1.9.4 update the offers were saved to a `wp-content/uploads/ponude` folder and sent to clients. An oversight on my part was not counting on the GDPR and the people's privacy. It was an honest mistake. And one I learned about because one of my client notified me about that due to the fact that his customer could see hers personal information coming from the sent pdf's.
Because of that fact I will no longer force the plugin to save the pdf's. Once they were sent to the customer the plugin will delete them from the server.

**Caution**

The new update will delete your old offers, so if you want to save them, you need to do that manually before a new offer is sent.

Then you should go to https://www.google.com/webmasters/tools/removals and request google to remove the search results for your invoices or offers.

I am truly sorry for this oversight, it wasn't done with intention.

----

The 1.5 update fixed the currency rate. I parsed the results from https://www.hnb.hr/tecajn/htecajn.htm and created my own currency rate array. I also tested it with included taxes and it seems to be working ok. If for any reason the taxes are not being calculated or pulled in the solo api, there must be some third party plugin that is messing stuff up. Be mindful of that.

----

The 1.4 update fixed a lot of issues with the taxes, and tax rates. Although this has been fixed, the taxes probably wont't be picked up by the SOLO API if you set the option in the WooCommerce to include taxes in the price of the product. This is why you should separate the two. Also when creating a shipping tax class, be sure to create it as aseparate class, and leave the standard tax class for the product. So for instance the product will have a standard tax of 5%, and the shipping can have a separate class with tax rate of 25% (be sure to tick the shipping checkbox in this class).
The including prices will be fixed in the next update.

----

The 1.3 update removed the predefined tax rates from the plugin, and is instead using WooCommerce taxes.
This means that you can have different tax for the item, and different for the shipping. Word of caution though, the Croatian law has predefined taxes of 0, 5, 13 or 25%. So in case you put the tax for the item and shipping anything other than that, the tax rate will be sent as 0 - so that your SOLO invoice or offer will pass, but with 0 tax rate.

Also be sure to separate those two taxes. For instance you can set the item tax rate as a standard tax rate, and then the shipping tax can be set as a reduced tax rate, and then just set the Shipping tax class to reduced tax rate in the WooCommerce options. If you put everything as a standard one, the tax rates will get mixed and the end price won't be correct (not sure how else to handle this since WooCommerce has a weird tax system).
