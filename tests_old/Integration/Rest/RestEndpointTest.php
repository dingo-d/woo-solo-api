<?php

namespace Tests\Integration\Rest;

use Codeception\TestCase\WPTestCase;
use IntegrationTester;
use MadeByDenis\WooSoloApi\Rest\BaseRoute;
use MadeByDenis\WooSoloApi\Rest\Endpoints\AccountDetails;
use MadeByDenis\WooSoloApi\Rest\Route;
use WP_REST_Request;
use WP_REST_Server;
use WP_UnitTest_Factory;

class RestEndpointTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	/**
	 * @var WP_REST_Server|WP_UnitTest_Factory
	 */
	private $server;

	/**
	 * @var string|WP_UnitTest_Factory
	 */
	private $route;

	public function setUp(): void
	{
		parent::setUp();

		global $wp_rest_server;
		$this->server = $wp_rest_server = new WP_REST_Server();
		do_action('rest_api_init');

		$this->route = '/' . BaseRoute::NAMESPACE_NAME . BaseRoute::VERSION . AccountDetails::ROUTE_NAME;
	}

	public function tearDown(): void
	{
		global $wp_rest_server;
		$wp_rest_server = null;

		parent::tearDown();
	}

	public function testRestEndpointIsRegistered()
	{
		(new AccountDetails())->register();
		$routes = $this->server->get_routes();

		$this->assertArrayHasKey($this->route, $routes);
	}

	public function testCallbackPermissionWorks()
	{
		$request = new WP_REST_Request(Route::READABLE, $this->route);

		$response = $this->server->dispatch($request);

		$this->assertEquals('rest_forbidden', $response->data['code']);
		$this->assertEquals('Sorry, you are not allowed to do that.', $response->data['message']);
		$this->assertEquals(401, $response->data['data']['status']);
	}

	public function testRestCallbackWorks()
	{
		$admin = $this->factory->user->create([
			'role' => 'administrator',
		]);

		set_current_user($admin);

		$request = new WP_REST_Request(Route::READABLE, $this->route);

		$response = $this->server->dispatch($request);
		$data = $response->get_data();

		$this->assertJson($data);
	}
}
