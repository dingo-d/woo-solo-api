<?php

declare(strict_types=1);

namespace Tests\Acceptance\Activation;

use AcceptanceTester;

class PluginActivationCest
{
	public function _before(AcceptanceTester $I)
    {
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginInstalled('woo-solo-api');
		$I->seePluginInstalled('woocommerce');
    }

	public function activatePluginSuccessfully(AcceptanceTester $I)
	{
		$I->activatePlugin('woo-solo-api');
		$I->seePluginActivated('woo-solo-api');
	}

	public function seeErrorBeingThrownOnPluginActivationIfWoocommerceIsNotActivated(AcceptanceTester $I)
	{
		$I->deactivatePlugin('woocommerce');
		$I->activatePlugin('woo-solo-api');
		$I->see('Plugin could not be activated because it triggered a fatal error.');
	}
}
