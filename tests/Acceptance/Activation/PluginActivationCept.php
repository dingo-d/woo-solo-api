<?php

//$I = new AcceptanceTester($scenario);

$I->loginAsAdmin();
$I->amOnPluginsPage();
$I->seePluginInstalled('woo-solo-api');
$I->seePluginInstalled('woocommerce');

$I->activatePlugin('woo-solo-api');
$I->seePluginActivated('woo-solo-api');

$I->deactivatePlugin('woo-solo-api');
$I->deactivatePlugin('woocommerce');
$I->activatePlugin('woo-solo-api');
$I->see('Plugin could not be activated because it triggered a fatal error.');
