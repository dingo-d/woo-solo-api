<?php

declare(strict_types=1);

namespace Tests\Integration\Exception;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Exception\{
	FailedToLoadView,
	InvalidUri,
	MissingManifest,
	PluginActivationFailure,
};

class ExceptionTest extends WPTestCase
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

	public function testFailedToLoadException()
	{
		$url = 'fake/uri.php';
		$exceptionMessage = 'Nonexistent view file.';
		$message = sprintf('Could not load the View URI "%1$s". Reason: "%2$s".', $url, $exceptionMessage);

		$this->expectException(FailedToLoadView::class);
		$this->expectExceptionMessage($message);

		throw FailedToLoadView::viewException($url, new \Exception($exceptionMessage));
	}

	public function testInvalidUriException()
	{
		$url = 'fake/uri.php';
		$message = sprintf('The View URI "%s" is not accessible or readable.', $url);

		$this->expectException(InvalidUri::class);
		$this->expectExceptionMessage($message);

		throw InvalidUri::fromUri($url);
	}

	public function testMissingManifestException()
	{
		$message = 'Asset manifest missing.';
		$this->expectException(MissingManifest::class);
		$this->expectExceptionMessage($message);

		throw MissingManifest::message($message);
	}

	public function testPluginActivationFailureException()
	{
		$message = 'Plugin failed to activate.';
		$this->expectException(PluginActivationFailure::class);
		$this->expectExceptionMessage($message);

		throw PluginActivationFailure::activationMessage($message);
	}
}
