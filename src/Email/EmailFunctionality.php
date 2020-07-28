<?php

/**
 * File holding EmailFunctionality class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Email
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\Email;

use MadeByDenis\WooSoloApi\Core\Registrable;

/**
 * EmailFunctionality class
 *
 * @since
 * @package MadeByDenis\WooSoloApi\Email
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
	 * @param string $name Name that is shown.
	 *
	 * @return string      Changed name.
	 */
	public function changeEmailFromName(string $name): string
	{
		$newName = esc_attr(get_option('solo_api_change_mail_from'));

		if (!empty($newName) && $newName !== '') {
			$name = $newName;
		}

		return $name;
	}
}
