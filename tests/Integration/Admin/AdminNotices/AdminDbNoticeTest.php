<?php

namespace Tests\Integration\Admin\AdminNotices;

use IntegrationTester;
use Codeception\TestCase\WPTestCase;
use MadeByDenis\WooSoloApi\Admin\AdminNotices\DatabaseTableMissingNotice;
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use WP_UnitTest_Factory;

class AdminDbNoticeTest extends WPTestCase
{
	/**
	 * @var IntegrationTester
	 */
	protected $tester;

	/**
	 * @var DatabaseTableMissingNotice|WP_UnitTest_Factory|null
	 */
	private $dbAdminNotice;

	public function setUp(): void
	{
		parent::setUp();

		$this->dbAdminNotice = new DatabaseTableMissingNotice(new SoloOrdersTable());
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}

	public function testShowAdminNoticeIfThereIsNoCustomDatabasePresent()
	{
		/**
		 * We can mock the databaseTableIsMissing() to always be true
		 * so that we can test the code after the conditional.
		 */
		$databaseTableStub = $this->createStub(SoloOrdersTable::class);

        // Configure the stub.
        $databaseTableStub->method('databaseTableIsMissing')
             				->willReturn(true);

        $dbNoticeClass = new DatabaseTableMissingNotice($databaseTableStub);

		$dbNoticeClass->register();

		ob_start();
		$dbNoticeClass->missingDatabaseTableNoticeCheck();
		$notice = ob_get_clean();

		$this->assertEquals('
<div class="notice notice-custom notice-warning is-dismissible">
	<p>Orders table seems to be missing. Please reactivate the plugin (deactivate and activate) to create it.</p>
</div>
', $notice);
	}

	public function testAdminNoticeWontBeShownIfThereIsADbTablePresent()
	{
		$this->dbAdminNotice->register();

		ob_start();
		$this->dbAdminNotice->missingDatabaseTableNoticeCheck();
		$notice = ob_get_clean();

		$this->assertEmpty($notice);
	}

	public function testRenderWillShowErrorIfViewIsMissing()
	{
		$render = $this->dbAdminNotice->render([]);

		$this->assertEquals('<pre>Undefined index: view_url</pre>', $render);
	}
}
