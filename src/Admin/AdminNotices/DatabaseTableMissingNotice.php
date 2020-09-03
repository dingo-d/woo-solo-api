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
use MadeByDenis\WooSoloApi\Core\Registrable;
use MadeByDenis\WooSoloApi\Core\Renderable;
use MadeByDenis\WooSoloApi\Database\SoloOrdersTable;
use MadeByDenis\WooSoloApi\View\EscapedView;
use MadeByDenis\WooSoloApi\View\TemplatedView;

/**
 * DatabaseTableMissingNotice class
 *
 * @package MadeByDenis\WooSoloApi\Admin\AdminNotices
 * @since 2.0.0
 */
class DatabaseTableMissingNotice implements Registrable, Renderable
{

	private const NOTICE_URI = 'views/missing-db-table-notice';

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('admin_notices', [$this, 'missingDatabaseTableNoticeCheck']);
	}

	public function missingDatabaseTableNoticeCheck()
	{
		if (!$this->databaseTableIsMissing()) {
			return;
		}

		$attributes['message'] = esc_html__(
			'Orders table seems to be missing. Please reactivate the plugin (deactivate and activate) to create it.',
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

	private function databaseTableIsMissing()
	{
		global $wpdb;

		$tableName = $wpdb->prefix . SoloOrdersTable::TABLE_NAME;

		$check = $wpdb->query(
			$wpdb->prepare("SELECT *
				FROM information_schema.tables
				WHERE table_schema = %s
					AND table_name = %s
				LIMIT 1;",
				$wpdb->dbname,
				$tableName
			)
		);

		if ($check === 0) {
			return true;
		}

		return false;

	}
}

