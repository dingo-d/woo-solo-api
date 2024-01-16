=== Woo Solo Api ===
Contributors: dingo_d
Tags: woocommerce, api, solo api, solo, api integration, shop, payment, woo
Requires at least: 6.1
Requires PHP: 7.4
Tested up to: 6.4.2
Stable tag: 3.3.0
WC requires at least: 7.0.0
WC tested up to: 8.4.0
License: MIT
License URI: https://opensource.org/licenses/MIT

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

== Requirements ==

* PHP 7.4 or greater
* WordPress 6.0 or above
* WooCommerce 7.0.0 or above
* Non IE browser

== Screenshots ==

1. After installation, you can go to the plugin settings and add your SOLO token
2. You can set up various options that are directly linked to how you want the invoice to look like
3. You can add some additional WooCommerce settings such as PIN and IBAN fields
4. You can add the mail settings that will be sent (if you choose to) to the client when the order is completed
5. You can test if your API key works properly and that communication with the Solo API works
6. You can check the sent orders, whether the order was sent to API, and if the customer got the PDF or not (or if error happened on the API)

== Changelog ==

= 3.3.0 =
Release Date: January 16th, 2024

### Added

* Additional api response errors in case of an email sending failure, for easier debugging

### Fixed

* Extended the timeout value of fetching PDF from Solo service

### Changed

* Minor package updates

= 3.2.0 =
Release Date: August 30th, 2023

### Added

* New filter for the address order field
* 0 days due date option for the order

### Fixed

* Fix the PIN field casting to integer (would strip the leading zero)

= 3.1.1 =
Release Date: August 9th, 2023

### Fixed

* Fix issue with saving of the fiscalization options

= 3.1.0 =
Release Date: August 9th, 2023

### Fixed

* Fix issue with fiscalization options
* Tested with WC 8.0.0 and WP 6.3

= 3.0.1 =
Release Date: January 31st, 2023

### Fixed

* Fix issue with the compiled container

= 3.0.0 =
Release Date: January 30th, 2023

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

= 2.3.0 =
Release Date: January 4th, 2023

This release will fix the affected change due to Croatia entering the Euro-zone.

### Added

* Add a fallback to checking if offers exist in case the invoices don't exist
* Add a way to clear Croatian National Bank API transient response

### Changed

* Update HNB API link to a new v3 working one
* Fix conversion logic for currencies

### Fixed

* Fix filter type for plugin link hook

= 2.2.0 =
Release Date: January 18th, 2022

Added:

* Added new filters (See FAQ section for more info)

Fixed:

* Fix the localization notice
* Fix PHPStan errors

= 2.1.0 =
Release Date: March 7th, 2021

Added:

* Added new filters and arguments to existing filters (See FAQ section for more info)
* Added a check if WooCommerce plugin is active to prevent fatal errors if you disable WooCommerce before this plugin

Fixed:

* Change name of the language translation files so that they work properly
  * In case the translation doesn't work automatically, place the woo-solo-api-hr.mo in the
    /wp-content/languages/plugins folder

= 2.0.8 =
Release Date: December 29th, 2020

Changed:

* Updated JS packages and webpack bundling process (updated to webpack 5)

Fixed:

* Update bundle of the assets and the PHP packages (possible cause to some issues in version 2.0.7?)

= 2.0.7 =
Release Date: December 28th, 2020

Fixed:

* Changed the typehint of the admin order fields hook.

= 2.0.6 =
Release Date: November 30th, 2020

Fixed:

* Add assets to the plugin. The GH Action tag is not working as intended so this is a manual fix.

= 2.0.5 =
Release Date: November 29th, 2020

Fixed:

* Fix the company name showing on the order and sending taxes option towards API.

= 2.0.4 =
Release Date: November 28th, 2020

Added:

* Clear error log message if order is manually triggered and succeeds.

Changed:

* Priority changed on the on completed WooCommerce hook.

Fixed:

* Remove shipping from all the variables, fix shipping tax calculation
  *The shipping does not concern us really, so no need to pull any fields from there.
  * Shipping was a bit changed, so it didn't pick anything up and reported 0 tax on taxable shipping.
* Fix the defaults in the settings (languages, checkout settings) in PHP.

= 2.0.3 =
Release Date: November 25th, 2020

Fixed:

* Add a default fallback on save in case a settings is undefined or null in settings

= 2.0.2 =
Release Date: November 24th, 2020

Fixed:

* Add a check if the options to be serialized exist at all before attempting to deserialize them

= 2.0.1 =
Release Date: November 23rd, 2020

Fixed:

* Autoload order in main plugin file, so that the first error can be thrown correctly, if it happens
* Fix the uninstall error because of a missing autoloader file
* Add fallbacks in case there is no gateway set, so that the JS doesn't fail when deserializing arrays

= 2.0.0 =
Release Date: November 22nd, 2020

This is a very big update. I called this the refactor, because the basic functionality of the plugin remained the same. But in all reality, this was a total plugin rewrite. You'll need to make sure you have PHP 7.3 installed on your server.

The options remained the same, so none of the settings on an already existing version should disappear. Plugin will
 need to be disabled and then enabled again once updated, so that the database table can be created.

There is one bug that is coming from the fact that the new settings page is built using Gutenberg blocks. Due to a
 nature of translations and how they work in GB, and the fact that I'm relying on webpack to bundle my assets. The
  translations are not working properly. There is an issue opened in WP-CLI ([make-json does not work for .jsx files
  ](https://github.com/wp-cli/i18n-command/issues/200)), but so far we don't have a fix for this. Once the fix comes
   out I'll update the plugin's translation files. The plugin's .po file is fully translated to Croatian, but we need a json file as well for the JavaScript translations.

The options page has been revamped and is built using Gutenberg components. So that's nice.

Api calls towards Solo service are now run as a background process. So don't be alarmed if the order doesn't appear
 immediately, or if the Solo invoice/order doesn't come immediately to the customer. Give it some time, and rummage
  around a bit, to trigger WordPress cron :D

A lot of issues were fixed. The plugin should be more stable and work better now.

Added:

* Tests - several acceptance tests and integration tests using wp-browser
* Added several rest routes for internal use
  * Account Details - fetches orders from SOLO API (for testing if api calls work)
  * Order Details and its collection - details about orders fetched from the database
* Added new database for sending orders to API
  * We need to keep track if the order was sent or not, and it's better to have a table with this information in it. We can update it when the API request is made, and when the process for triggering user email happens. This gives us more control later on if we need to debug something (missing emails, missing requests, etc.).
* Add privacy related functionality
  * This functionality hooks into WP's own core privacy functionality. So that if the user requests their data to be
  either exported or deleted we remove the data in the custom table we created.
* Added filters for modifying certain data in the plugin
* Link the invoice from the Solo service with the WooCommerce invoice

Changed:

* Refactored the entire plugin structure to use dependency injection, autowiring, PSR-4 autoloading and PSR-12 coding
 standards
* Replaced old settings page with new, core editor powered settings page
* Settings page now includes WooCommerce navbar
* Sending any remote call towards Solo API is now done utilizing WordPress cron.
  * It's not ideal, as Wordpress cron is
 not a real cron, but it seems to be working. With queueing calls we are less likely to trigger API throttling from
  the Solo Api. Oftentimes there would be no orders created in the Solo service, and the user would be none the wiser
   what happened. By queueing we avoid this throttling. Also, we have a better way of logging errors from the API.

Removed:

* Removed the filter for PDF file types as those are no longer saved in the media uploads folder

Fixed:

* If 2 transactions are marked as complete, only one will send the call to solo API for completion
  * Fixed by adding a db with checks from the database
* When changing the order from on hold to completed in the order screen the API call is made again
  * Fixed by adding a db with checks from the database
* Make company field mandatory if R1 type of invoice is selected
* Check if the offer/bill is created twice
  * Fixed by adding a db with checks from the database
* Plugin recognizes Braintree and Corvus payment gateways

= 1.9.6 =
Release Date: May 18th, 2019

Changed:

* Tested with WordPress 5.2
* Tested with WooCommerce 3.6.3

= 1.9.5 =
Release Date: December 9th, 2018

Added:

* Added escaping to activation messages

Changed:

* Updated `composer.json` (development only)
* Updated `phpcs.xml.dist` (development only)
* Tested with WordPress 5.0

Fixed:

* Fixed issue with multiple requests being made towards the SOLO service
* Fixed the issue with no emails being send on order status change
* Fixed the wrong plugin settings link on the plugins admin page
* Fixed `.travis` build (development only)
* Minor autoload fixes (removed manual requirement for activation hook)

= 1.9.4 =
Release Date: December 3rd, 2018

Added:

* Added removal of all offers

Changed:

* Changed the name of the offers and invoices

Fixed:

* Fixed important GDPR issue – no longer saving offers and invoices because they contain sensitive information, and
 google will crawl your site and make them public


= 1.9.3 =
Release Date: August 26th, 2018

Added:

* Manually add translations for the recalculated note, since locale manipulation was causing site language change

Fixed:

* Fix the status check for the currency list

= 1.9.2 =
Release Date: July 11th, 2018

Fixed:

* Minor fix – put WC() in the global scope

= 1.9.1 =
Release Date: June 30th, 2018

Fixed:

* Minor fix in the Admin class constructor (string typehinting is not available prior to PHP 7)

= 1.9.0 =
Release Date: June 6th, 2018

Added:

* Added the ability to translate the invoice/offer based on the selected and available language

Changed:

* Moved the Solo API options under the WooCommerce menu
* Extended the due date time to include 1-6 days in addition to 1-3 weeks

= 1.8.1 =
Release Date: June 6th, 2018

Added:

* Add a check if no shipping is selected in the WooCommerce options

Changed:

* Minor code refactor (constants instead of properties in the main class)
* Replaced built in autoloader for composers autoloader

= 1.8.0 =
Release Date: May 7th, 2018

Added:

* Add test method that returns all receipts from Solo API to check if the plugin is working properly.

Changed:

* Minor visual adjustments

= 1.7.5 =
Release Date: April 29th, 2018

Added:

* Add fallback method for fetching currency rates in case the `allow_url_fopen is disabled.

= 1.7 =
Release Date: March 24th, 2018

Added:

* Added payment types for each payment gateway

Fixed:

* Tax fixes – tax class wasn't working on changing the order to status 'complete'
* Added travis integration (development)
* Added code standards definition (development)

= 1.6 =
Release Date: March 3rd, 2018

Fixed:

* Fixed language issue on the invoices (props to ivoks)
* PHPCS fixes

= 1.5 =
Release Date: March 1st, 2018

Changed:

* Set the transient validity to 6 hours so every 6 hours it will expire and be created again

Fixed:

* Fixed the currency rate to pull it from the official hnb site

= 1.4 =
Release Date: February 25th, 2018

Added:

* Added automatic conversion rate from Croatian National Bank (https://www.hnb.hr/temeljne-funkcije/monetarna-politika/tecajna-lista/tecajna-lista)
* Add a fix for duplicated orders – you can now choose if you want to make SOLO API call on the checkout or when
 changing order status manually

Fixed:

* Fix for taxes rounding error on shipping

= 1.3 =
Release Date: February 25th, 2018

Added:

* Add additional debug methods
* Add tax check – tax rate can be separate for shipping and for items, and are handled by WooCommerce

Fixed:

* Code sniffer fixes

= 1.2 =
Release Date: December 10th, 2017

Changed:

* Renamed classes – shorten the name of the classes and changed file names

Fixed:

* Fixed a bug that prevented sending of the pdf bill if the type of the bill was offer (ponuda)

= 1.1 =
Release Date: November 23rd, 2017

Added:

* Added multiple invoice type selection based on payment type with appropriate fiscalization check

Changed:

* Fiscalization will be disabled for offer by default, and enabled only on invoice type

= 1.0.1 =
Release Date: November 22nd, 2017

Fixed:

* Typo fix

= 1.0 =
Release Date: November 22nd, 2017

* Initial release

== Upgrade Notice ==

= Version 2 update =

This is a very big update. I called this the refactor, because the basic functionality of the plugin remained the same. But in all reality, this was a total plugin rewrite.

The options remained the same, so none of the settings on an already existing version should disappear. Plugin will
 need to be disabled and then enabled again once updated, so that the database table can be created.

There is one bug that is coming from the fact that the new settings page is built using Gutenberg blocks. Due to a nature of translations and how they work in GB, and the fact that I'm relying on webpack to bundle my assets. The translations are not working properly. There is an issue opened in WP-CLI (https://github.com/wp-cli/i18n-command/issues/200), but so far we don't have a fix for this. Once the fix comes out I'll update the plugin's translation files. The plugin's .po file is fully translated to Croatian, but we need a json file as well for the JavaScript translations.

The options page has been revamped and is built using Gutenberg components. So that's nice.

Api calls towards Solo service are now run as a background process. So don't be alarmed if the order doesn't appear immediately, or if the Solo invoice/order doesn't come immediately to the customer. Give it some time, and rummage around a bit, to trigger WordPress cron :D

A lot of issues were fixed. The plugin should be more stable and work better now.

'''Hrvatski prijevod''':

Ovo je veoma veliki update dodatka. Funkcionalnosti dodatka su ostale iste, ali je način na koji dodatak radi veoma izmjenjen. Da bi plugin radio, vaš server mora podržavati PHP 7.3 ili 7.4.

Prilikom updatea, trebat ce te ga onemogućiti i ponovo omogućiti, da biste kreirali novu tablicu u bazi, koja služi za provjeru jesu li se račun/ponuda poslali na Solo servis. Nemojte deinstalirati dodatak, jer to ce maknuti sve podatke koje imate spremljene (sve opcije).

Postoji jedan bug koji je posljedica toga kako se kod pakira i činjenice da se koriste Gutenberg komponente kod kreiranja stranica opcija plugina. Iako prijevod postoji (.po i .mo file sa prijevodima su uključene u plugin), način na koji Gutenberg kontrolira prijevod, znači da trenutno ne može dohvatiti te prijevode (https://github.com/wp-cli/i18n-command/issues/200). Stoga će opcije dodatka biti na engleskom jeziku, dokle god se taj problem ne riješi.

Također, api pozivi prema Solo servisu se izvršavaju kao pozadinski procesi. To znači da kupac neće odmah dobiti e-mail s računom/ponudom koja je generirana na Solo servisu, nego će se to desiti nakon 15ak do 30ak sekundi. Pošto se koristi WordPressov cron za tu funkcionalnost, bitno je da netko bude prisutan na stranici i da se navigira po njoj (ili u adminu ili na frontu). To je jednostavno kako WordPressov cron funkcionira. Razlog zašto su se ti pozivi morali napraviti kao pozadinski procesi, jest to da Solo API ima zaštitu od napada napravljenu da se unutar 15 sekundi ne smiju desiti dva API poziva, inače bit takvi pozivi bili odbačeni, i korisnik ne bi dobio PDF račun ili se ne bi kreirala ponuda/račun na Solo servisu.

Osim toga, dosta problema je riješeno sa ovim updateom, CorvusPay i Braintree integracije su prepoznate i rade sa dodatkom.

= 1.9.4 Version privacy fix =

Pre 1.9.4 update the offers were saved to a `wp-content/uploads/ponude` folder and sent to clients. An oversight on my part was not counting on the GDPR and the people's privacy. It was an honest mistake. And one I learned about because one of my client notified me about that due to the fact that his customer could see hers personal information coming from the sent pdf's.
Because of that fact I will no longer force the plugin to save the pdf's. Once they were sent to the customer the plugin will delete them from the server.

**Caution**

The new update will delete your old offers, so if you want to save them, you need to do that manually before a new offer is sent.

Then you should go to https://www.google.com/webmasters/tools/removals and request google to remove the search results for your invoices or offers.

I am truly sorry for this oversight, it wasn't done with intention.

= 1.5 Currency rate update =

The 1.5 update fixed the currency rate. I parsed the results from https://www.hnb.hr/tecajn/htecajn.htm and created my own currency rate array. I also tested it with included taxes and it seems to be working ok. If for any reason the taxes are not being calculated or pulled in the solo api, there must be some third party plugin that is messing stuff up. Be mindful of that.

= 1.4 Tax and tax rates fixes =

The 1.4 update fixed a lot of issues with the taxes, and tax rates. Although this has been fixed, the taxes probably won't be picked up by the SOLO API if you set the option in the WooCommerce to include taxes in the price of the product. This is why you should separate the two. Also when creating a shipping tax class, be sure to create it as a separate class, and leave the standard tax class for the product. So for instance the product will have a standard tax of 5%, and the shipping can have a separate class with tax rate of 25% (be sure to tick the shipping checkbox in this class).
The including prices will be fixed in the next update.

= 1.3 Taxes fixes =

The 1.3 update removed the predefined tax rates from the plugin, and is instead using WooCommerce taxes.
This means that you can have different tax for the item, and different for the shipping. Word of caution though, the Croatian law has predefined taxes of 0, 5, 13 or 25%. So in case you put the tax for the item and shipping anything other than that, the tax rate will be sent as 0 - so that your SOLO invoice or offer will pass, but with 0 tax rate.

Also be sure to separate those two taxes. For instance you can set the item tax rate as a standard tax rate, and then the shipping tax can be set as a reduced tax rate, and then just set the Shipping tax class to reduced tax rate in the WooCommerce options. If you put everything as a standard one, the tax rates will get mixed and the end price won't be correct (not sure how else to handle this since WooCommerce has a weird tax system).

== Frequently Asked Questions ==

= Why is the PHP 7.3 required? =

PHP 7.4 is in the end of life(https://www.php.net/supported-versions.php) phase. You should really move on to PHP 8.

If you have older PHP versions, you are not only doing a disservice to the planet, you're also harming your own site speed and security. You can always ask your hosting provider to fix this for you, or if you have control over cPanel on shared hosting, you are likely to be able to update PHP version yourself. Just make sure you tick all the required PHP
extensions (https://make.wordpress.org/hosting/handbook/handbook/server-environment/#php-extensions).

= I didn't receive an invoice when testing the plugin. What's wrong? =

Give it a minute, scroll your site. The API communication happens using WP cron, which depends on the site activity to work. You can always check the 'Solo API order details' tab in the settings to see if there was some error from the API.
If that fails, turn on the debug mode (https://wordpress.org/support/article/debugging-in-wordpress/) and try to test again. If there was a fatal error it should be shown in the logs.

In the version 3 of the plugin, for every failed request you'll be able to see the error and try to rerun the call towards the API.

= This plugin doesn't work with XYZ plugin =

Could be. I didn't test it out with every plugin in the wild. That takes time. And money. In the near future I might be able to do some paid work, but that will depend on many things.

Please don't try to guilt-trip me to work on your site specific issues. I will try to help to the best of my abilities.
But the plugin is free, so don't expect me to work miracles. Also, don't expect me to
drop everything just to work on your issue. If I don't answer in a day or two, be patient. I have a private life as
well (shocker, I know) :D

If you want me to add a new feature to the plugin you can contact me and pay me to work on the feature you need. My rate is 50€/hr.

Note that some features are not possible to make within this plugin. For instance, some apps and plugins will utilize WooCommerce REST API endpoints to trigger orders. Their API is poorly written, and when you do these kind of actions, the hooks that will look if something changed won't get triggered, which means this plugin cannot know of the order status. It is what it is. I could make some kind of listener/cron jobs to manually check these things, but this is a paid feature.

= Can I modify how the email looks, or customer notice? =

Sure you can, besides actually adding things in the settings of the plugin you can modify certain things using these hooks:

--------------

Overwrite the checkout request for specific payment cases

This filter is used if you have globally selected the API request to happen on
manual status change of the order, but you want to allow some payment gateways to
execute on the checkout.

Usage:

<code>
add_filter('woo_solo_api_overwrite_request_on_checkout', 'my_payment_processor_overwrite', 10, 2);

function my_payment_processor_overwrite($sendControl, $order) {
  // Say we want to allow api call on checkout for direct bank transfer (bacs),
  // and all others on status change.

  // Check the payment gateway from the order.
  $paymentMethod = $order->get_payment_method();

  // Check if the current payment method is the one you want to overwrite.
  if ($paymentMethod === 'bacs') {
    return 'checkout';
  }

  // Default fallback.
  return $sendControl;
}
</code>

<code>
@param string $sendControl Type of send control. Can be 'checkout' or 'status_change'.
@param WC_Order $order Order object.
</code>

--------------

Filters the custom message for customer note

If you need to extend the customer note, you can just hook to this filter
and modify the existing content

Usage:

<code>
add_filter('woo_solo_api_modify_customer_note', 'my_customer_filter', 10, 2);

function my_customer_filter($customerNote, $order) {
  // (maybe) modify $customerNote.
  return $customerNote;
}
</code>

<code>
@param string $customerNote Existing customer note.
@param WC_Order $order Order object
</code>

--------------

Filters the Solo API request body before it's being prepared for sending

If you need to modify the request towards the SOLO API,
you can just hook to this filter and modify the request body.

Usage:

<code>
add_filter('woo_solo_api_modify_request_body', 'my_customer_filter', 10, 2);

function my_customer_filter($requestBody, $order) {
  // (maybe) modify $requestBody.
  return $requestBody;
}
</code>

<code>
@param array $requestBody Existing customer note.
</code>

--------------

Modify the email message from the options

Email message can be set in the options and will be outputted here.
If, for whatever reason we want to modify it some more, we can do that here.

Usage:

<code>
add_filter('woo_solo_api_modify_options_email_message', 'my_message_filter', 10, 3);

function my_message_filter($emailMessage, $orderId, $email) {
  // (maybe) modify $emailMessage.
  return $emailMessage;
}
</code>

<code>
@param string $emailMessage Email message from options to filter.
@param int    $orderId Order ID.
@param string $email Email address of the person for whom this message needs to be send to.
</code>

--------------

Modify the default email message

If you don't set the message in the options, you can still filter the default one.

Usage:

<code>
add_filter('woo_solo_api_modify_default_email_message', 'my_message_filter', 10, 3);

function my_message_filter($defaultMessage, $orderId, $email) {
  // (maybe) modify $defaultMessage.
  return $defaultMessage;
}
</code>

<code>
@param string $defaultMessage Email message to filter.
@param int    $orderId Order ID.
@param string $email Email address of the person for whom this message needs to be send to.
</code>

--------------

Modify the email title from the options

Email title for the customer can be set in the options,
but you can modify it further with this filter.

Usage:

<code>
add_filter('woo_solo_api_modify_options_email_title', 'my_title_filter', 10, 2);

function my_title_filter($emailTitle, $orderId) {
  // (maybe) modify $emailTitle.
  return $emailTitle;
}
</code>

<code>
@param string $emailTitle Email title.
@param int    $orderId Order ID.
</code>

--------------

Modify the default email title from the options

If you don't use the title from the options, you can use default one.
And modify it.

Usage:

<code>
add_filter('woo_solo_api_modify_default_email_title', 'my_title_filter', 10, 2);

function my_title_filter($defaultTitle, $orderId) {
  // (maybe) modify $defaultTitle.
  return $defaultTitle;
}
</code>

<code>
@param string $emailTitle Email title.
@param int    $orderId Order ID.
</code>

--------------

Filter email headers

When email to customer is sent, maybe you want to add something more. In that
case you'll probably need to modify the headers sent with the email.
Default ones are

<code>
[
	'MIME-Version: 1.0',
	'Content-Type: text/html',
];
</code>

You can add to that list.

Usage:

<code>
add_filter('woo_solo_api_email_headers', 'my_custom_email_headers', 10);

function my_custom_email_headers($headers) {
  // (maybe) modify $headers.
  return $headers;
}
</code>

<code>
@param array $headers Email headers to pass to wp_mail.
</code>

--------------

Filter the 'from' name set from the options

Usage:

<code>
add_filter('woo_solo_api_change_email_from_name', 'my_custom_from_name', 10);

function my_custom_from_name($name) {
  // (maybe) modify $name.
  return $name;
}
</code>

<code>
@param string $name Name to change in the "From" field.
</code>

--------------

Adds a global discount

WooCommerce will handle counting the discounts for us.
This is why this is set to 0.
We can hook into this if we want to change it.
But this hook will affect every item. So use it with care.

Usage:

<code>
add_filter('woo_solo_api_add_global_discount', 'my_global_discount', 10, 1);

function my_global_discount($globalDiscount) {
  // (maybe) modify $globalDiscount.
  return $globalDiscount;
}
</code>

<code>
@param int $globalDiscount The value of the global discount to apply to every item.
</code>

--------------

Modify tax rates

This hook is used to set different tax rates for items based on certain criteria.
For instance, if you want to modify taxes based on location you can change it here
(if for some reason it's not working from the default settings).

Usage:

<code>
add_filter('woo_solo_api_modify_tax_rate', 'my_tax_rate', 10, 3);

function my_tax_rate($taxRate, $itemData, $taxRates) {
  // (maybe) modify $taxRate.
  return $taxRate;
}
</code>

<code>
@param float $taxRate  The value of the tax rate for the current order item.
@param array $itemData The data for the current order item.
@param array $taxRates The value of the tax rates for the current order item.
</code>

--------------

Modify the bill type

Use this filter if you need to dynamically change the bill type.

Usage:

<code>
add_filter('woo_solo_api_modify_bill_type', 'my_customer_filter', 10, 3);

function my_customer_filter($billType, $paymentMethod, $order) {
  // (maybe) modify $billType, based on some external parameters or $paymentMethod or $order.
  return $billType;
}
</code>

<code>
@param string $billType Existing bill type.
@param string $paymentMethod Selected payment method for the current order.
@param object|WC_Order $order Current order.
</code>

--------------

Filter the unit measure for the current item

Use this filter if you need to dynamically change measure for the item.
Unit measure *MUST* be an integer in the list of measures (check the API documentation for details).

<code>
add_filter('woo_solo_api_modify_item_measure', 'my_customer_filter', 10, 4);

function my_customer_filter($measure, $itemNo, $order, $itemData) {
  // Modify multiple item numbers in your order based on some settings, meta value, etc.
  switch ($itemNo) {
    case 0:
      $measure = 7;
      break;
    case 1:
      $measure = 19;
      break;
    default:
      $measure = 2;
      break;
  }

  // Or filter based on $order object or $itemData which contains other information about the product.

  return $measure;
}
</code>

<code>
@param int $measure Current unit measure.
@param int $itemNo Current item in the list.
@param object|WC_Order $order Current order.
@param array $itemData Current item data.
</code>

--------------

Filter the unit measure for the current shipping item

Use this filter if you need to dynamically change measure for the shipping item.
Unit measure *MUST* be an integer corresponding to the list of measures.

Be careful when changing the measure using the above filter, as the shipping item should probably be a `piece (2)`.

<code>
add_filter('woo_solo_api_modify_shipping_item_measure', 'my_customer_filter', 10, 4);

function my_customer_filter($measure, $itemNo, $order, $itemData) {
    return 2; // Corresponds to: piece.
}
</code>

<code>
@param int $measure Current shipping unit measure. Will be picked up by whatever is in the default measure.
@param int $itemNo Current item in the list.
@param object|WC_Order $order Current order.
@param object|null $shippingObject Current shipping item data.
</code>
