<?php

declare(strict_types=1);

namespace Tests\Acceptance\Activation;

use AcceptanceTester;

class PluginActivationCest
{
	function _before(AcceptanceTester $I)
    {
		$I->loginAsAdmin();
		$I->amOnPluginsPage();
		$I->seePluginInstalled('woo-solo-api');
		$I->activatePlugin('woo-solo-api');
		$I->seePluginActivated('woo-solo-api');
    }

    public function _after(AcceptanceTester $I)
    {
    }

	public function pluginCanBeActivated(AcceptanceTester $I)
	{
	}
}
