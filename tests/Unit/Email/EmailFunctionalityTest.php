<?php

namespace Tests\Unit\Email;

use Codeception\Test\Unit;
use Brain\Monkey;
use Brain\Monkey\Functions;
use MadeByDenis\WooSoloApi\Email\EmailFunctionality;

class EmailFunctionalityTest extends Unit
{

	/**
	 * @var EmailFunctionality
	 */
	private $emailFunctionality;

	protected function _before() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
	{
		Monkey\setUp();
		Functions\stubEscapeFunctions();

		$this->emailFunctionality = new EmailFunctionality();
	}

	protected function _after() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
	{
		Monkey\tearDown();
	}

	public function testHooksAdded()
	{
		$this->emailFunctionality->register();

		$this->assertNotFalse(
			has_filter('wp_mail_from_name', 'MadeByDenis\WooSoloApi\Email\EmailFunctionality->changeEmailFromName()'),
			'Filter hook not registered'
		);

		$this->assertSame(
			10,
			has_filter(
				'wp_mail_from_name',
				'MadeByDenis\WooSoloApi\Email\EmailFunctionality->changeEmailFromName()'
			),
			'wp_mail_from_name filter not registered with default priority of 10'
		);
	}

	public function testEmailChangeFromNameWorks()
	{

		Functions\when('get_option')->justReturn('');

		$newName = 'John';
		$changedName = $this->emailFunctionality->changeEmailFromName($newName);

		$this->assertEquals($newName, $changedName, 'Name should remain the same');


		$updatedName = 'Jerry';
		Functions\when('get_option')->justReturn($updatedName);

		$changedName = $this->emailFunctionality->changeEmailFromName($newName);

		$this->assertEquals($updatedName, $changedName, 'Name should be changed the same');
	}
}
