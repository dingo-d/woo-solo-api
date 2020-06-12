<?php

declare(strict_types=1);

namespace Tests\Integration\Utils;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Utils\ErrorCodes;

class ErrorCodesTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	public function setUp(): void
	{
		parent::setUp();
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}

	public function testErrorCodesTrait()
	{
		$mock = $this->getMockForTrait(ErrorCodes::class);

		$this->assertEquals('Number not only once is invalid.', $mock->getErrorMessage('nonce'));
		$this->assertEquals('User is not authorized to do this action.', $mock->getErrorMessage('authorization'));
		$this->assertEquals('Error occurred', $mock->getErrorMessage('default'));
	}
}
