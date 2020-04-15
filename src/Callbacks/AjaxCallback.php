<?php

/**
 * File holding AjaxCallback class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Callbacks
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Callbacks;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * AjaxCallback class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Callbacks
 */
abstract class AjaxCallback implements Invokable, Registrable
{

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		$callback_name = $this->getActionName();

		add_action("wp_ajax_{$callback_name}", [$this, 'callback']);

		// Add nopriv action.
		if ($this->isPublic()) {
			add_action("wp_ajax_nopriv_{$callback_name}", [$this, 'callback']);
		}
	}

	/**
	 * @inheritDoc
	 */
	abstract public function callback();

	/**
	 * Returns true if the callback should be public
	 *
	 * @return boolean true if callback is public.
	 */
	abstract protected function isPublic(): bool;

	/**
	 * Get name of the callback action
	 *
	 * @return string Name of the callback action.
	 */
	abstract protected function getActionName(): string;
}
