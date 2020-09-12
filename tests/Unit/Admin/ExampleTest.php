<?php

namespace Tests\Unit\Admin;

use Codeception\Test\Unit;
use Brain\Monkey;

class ExampleTest extends Unit
{

	protected function _before() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
	{
		Monkey\setUp();
	}

	protected function _after() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
	{
		Monkey\tearDown();
	}

	public function testExample()
	{
		$this->assertTrue(true, 'Assertion is not true');
	}
}
