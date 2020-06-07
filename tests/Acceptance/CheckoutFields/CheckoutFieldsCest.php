<?php
$I = new AcceptanceTester($scenario);

$I->loginAsAdmin();
$I->amOnPage('wp-admin/admin.php?page=solo_api_options');
$I->wait(10);
$I->click('Additional Settings');
$I->click('Enable the PIN field on the billing and shipping from in the checkout');
$I->click('Enable the IBAN field on the billing and shipping from in the checkout');
$I->click('Save settings');

$I->wait(10);

$I->amOnPage('/product/album/');
$I->click('Add to cart');
$I->click('View cart');
$I->click('Proceed to checkout');
$I->see('PIN number (optional)');
$I->see('IBAN number (optional)');
