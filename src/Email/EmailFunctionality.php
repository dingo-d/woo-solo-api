<?php

/**
 * File holding EmailFunctionality class
 *
 * @package MadeByDenis\WooSoloApi\Email
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Email;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * Email functionality
 *
 * Adds modifications (global!) to the email headers.
 *
 * @package MadeByDenis\WooSoloApi\Email
 * @since 2.0.0
 */
class EmailFunctionality implements Registrable
{

	/**
	 * @inheritDoc
	 */
	public function register(): void
	{
		add_action('wp_mail_from_name', [$this, 'changeEmailFromName']);
	}

	/**
	 * Change the email 'From' name that is send from WordPress
	 *
	 * @since 2.0.0 Added a filter that can be hooked into.
	 * @since 1.0.0
	 *
	 * @param string $name Name that is shown.
	 *
	 * @return string Changed name.
	 */
	public function changeEmailFromName(string $name): string
	{
		$newName = esc_attr(get_option('solo_api_change_mail_from'));

		if (!empty($newName) && $newName !== '') {
			$name = $newName;
		}

		return apply_filters('woo_solo_api_change_email_from_name', $name);
	}
}
