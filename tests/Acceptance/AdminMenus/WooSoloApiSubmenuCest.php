<?php

$I->loginAsAdmin();
$I->amOnPluginsPage();
$I->activatePlugin('woocommerce');
$I->activatePlugin('woo-solo-api');

$I->amOnPage('wp-admin/admin.php?page=solo_api_options');
$I->wait(10);
$I->see('Solo API token');
