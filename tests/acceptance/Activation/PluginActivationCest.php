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
		$I->seePluginInstalled('action-scheduler');
    }

	public function activatePluginSuccessfully(AcceptanceTester $I)
	{
		$I->activatePlugin('woo-solo-api');
		$I->seePluginActivated('woo-solo-api');
	}

	public function seeErrorBeingThrownOnPluginActivationIfWooCommerceIsNotActivated(AcceptanceTester $I)
	{
		$I->deactivatePlugin('woocommerce');
		$I->activatePlugin('woo-solo-api');
		$I->see('Plugin could not be activated because it triggered a fatal error.');
	}

	public function seeScheduledActionsPage(AcceptanceTester $I)
	{
		$I->activatePlugin('action-scheduler');
		$I->amOnPage('/wp-admin/tools.php?page=action-scheduler');
		$I->see('Scheduled Actions');
	}
}
