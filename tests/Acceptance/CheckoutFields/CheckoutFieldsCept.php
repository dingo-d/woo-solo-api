<?php

$I = new AcceptanceTester($scenario);

$I->loginAsAdmin();
$I->amOnPage('wp-admin/admin.php?page=solo_api_options');

$I->waitForElementVisible('.components-panel__body', 10);
$I->click('Additional Settings');
$I->checkOption('Enable the PIN field on the billing and shipping from in the checkout');
$I->checkOption('Enable the IBAN field on the billing and shipping from in the checkout');
$I->click('Save settings');
$I->waitForElementVisible('.components-snackbar:not(.is-hidden)', 10);

$I->amOnPage('/product/album/');
$I->click('Add to cart');
$I->click('View cart');
$I->click('Proceed to checkout');
$I->see('PIN number (optional)');
$I->see('IBAN number (optional)');
