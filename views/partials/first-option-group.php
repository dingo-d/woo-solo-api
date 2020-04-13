<?php

/**
 * Options page partials
 *
 * Used for displaying one tab of options
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Views
 */

namespace MadeByDenis\WooSoloApi\Views;

?>
<input id="token" type="radio" name="tabs" />
<label for="token"><?php _e('Solo API token', 'woo-solo-api'); ?></label>
<div class="content">
	<label for="apiToken" class="subtitle">
		<?php esc_html_e('Enter your personal token that you obtained from you SOLO account.', 'woo-solo-api'); ?>
	</label>
	<input
		class="js-api-token"
		type="text"
		id="apiToken"
		name="apiToken"
		value="<?php echo esc_attr($this->token); ?>"
	/>
</div>
