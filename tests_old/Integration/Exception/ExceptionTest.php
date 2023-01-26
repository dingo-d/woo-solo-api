<?php

namespace Tests\Integration\Exception;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Exception\{
	ApiRequestException,
	FailedToLoadView,
	InvalidUri,
	MissingManifest,
	PluginActivationFailure,
	OrderValidationException,
	WpException
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

	public function testApiRequestException()
	{
		$code = 400;
		$message = 'Api error.';
		$this->expectException(ApiRequestException::class);
		$this->expectExceptionMessage("API request error happened. {$message}. (Error code {$code}).");

		throw ApiRequestException::apiResponse($code, $message);
	}

	public function testOrderValidationException()
	{
		$order = 1;
		$this->expectException(OrderValidationException::class);
		$this->expectExceptionMessage('Order expected, integer returned.');

		throw OrderValidationException::invalidOrderType($order);
	}

	public function testWpExceptionException()
	{
		$code = 404;
		$message = 'WP error.';
		$this->expectException(WpException::class);
		$this->expectExceptionMessage("WordPress internal error happened. {$message}. (Error code {$code}).");

		throw WpException::internalError($code, $message);
	}
}
