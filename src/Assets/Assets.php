<?php

/**
 * File holding Assets interface
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Assets
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Assets;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * Assets interface
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Assets
 */
interface Assets extends Registrable
{
	/**
	 * Enqueue styles
	 *
	 * @since 2.0.0
	 *
	 * @param string $hookSuffix Page suffix.
	 * @return mixed
	 */
	public function enqueueStyles(string $hookSuffix);

	/**
	 * Enqueue scripts
	 *
	 * @since 2.0.0
	 *
	 * @param string $hookSuffix Page suffix.
	 * @return mixed
	 */
	public function enqueueScripts(string $hookSuffix);
}
