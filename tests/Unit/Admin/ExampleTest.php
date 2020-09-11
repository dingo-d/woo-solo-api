<?php

namespace Tests\Unit\Admin;

use Codeception\Test\Unit;

use Brain\Monkey;

class ExampleTest extends Unit {

	protected function _before() {
        Monkey\setUp();
    }

    protected function _after() {
		Monkey\tearDown();
    }

	public function testExample()
	{
		$this->assertTrue(true, 'Assertion is not true');
    }
}
