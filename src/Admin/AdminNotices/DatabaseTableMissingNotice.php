<?php

/**
 * File holding DatabaseTableMissingNotice class
 *
 * @package MadeByDenis\WooSoloApi\Admin\AdminNotices
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Admin\AdminNotices;

use Exception;
use MadeByDenis\WooSoloApi\Core\{Registrable, Renderable};
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\View\{EscapedView, TemplatedView};

/**
 * Admin notice when database table is missing
 *
 * This class handles the admin_notices logic that will be shown if user
 * just updates the plugin without deactivating it.
 *
 * @package MadeByDenis\WooSoloApi\Admin\AdminNotices
 * @since 2.0.0
 */
class DatabaseTableMissingNotice implements Registrable, Renderable
{

	/**
	 * @var string View path
	 */
	private const NOTICE_URI = 'views/missing-db-table-notice';

	/**
	 * @var SoloOrdersTable
	 */
	private $ordersTable;

	/**
	 * DatabaseTableMissingNotice constructor
	 *
	 * @param SoloOrdersTable $ordersTable Dependency that manages database concern.
	 */
	public function __construct(SoloOrdersTable $ordersTable)
	{
		$this->ordersTable = $ordersTable;
	}

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('admin_notices', [$this, 'missingDatabaseTableNoticeCheck']);
	}

	/**
	 * Admin notice callback
	 *
	 * Shows the admin notice if the database table is missing.
	 *
	 * @return void
	 */
	public function missingDatabaseTableNoticeCheck(): void
	{
		if (!$this->ordersTable->databaseTableIsMissing()) {
			return;
		}

		$attributes['message'] = esc_html__(
			'Woo Orders table seems to be missing. Please reactivate the plugin (deactivate and activate) to create it.',
			'woo-solo-api'
		);

		$attributes['view_url'] = self::NOTICE_URI;

		echo $this->render((array)$attributes);
	}

	/**
	 * Render the current Renderable.
	 *
	 * We need to pass the view URL using the attributes.
	 *
	 * @param array $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 */
	public function render(array $context = []): string
	{
		try {
			$view = new EscapedView(
				new TemplatedView($context['view_url'])
			);

			return $view->render($context);
		} catch (Exception $exception) {
			return sprintf(
				'<pre>%s</pre>',
				$exception->getMessage()
			);
		}
	}
}
