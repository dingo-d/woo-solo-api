<?php

$I = new AcceptanceTester($scenario);

$I->loginAsAdmin();
$I->amOnPluginsPage();
$I->activatePlugin('woocommerce');
$I->activatePlugin('woo-solo-api');

$I->amOnPage('wp-admin/admin.php?page=solo_api_options');
$I->waitForElementVisible('.components-panel__body', 10);
$I->see('Solo API token');
