<?php

/**
 * File holding Assets interface
 *
 * @package MadeByDenis\WooSoloApi\Assets
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Assets;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * Assets interface
 *
 * This interface is responsible for style and scripts enqueueing.
 *
 * @package MadeByDenis\WooSoloApi\Assets
 * @since 2.0.0
 */
interface Assets extends Registrable
{
	/**
	 * Enqueue scripts
	 *
	 * @since 2.0.0
	 *
	 * @param string $hookSuffix Page suffix.
	 *
	 * @return mixed
	 */
	public function enqueueScripts(string $hookSuffix);
}
