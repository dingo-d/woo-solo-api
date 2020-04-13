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
<input class="options-wrapper__input" id="token" type="radio" name="tabs" checked="checked"/>
<label class="options-wrapper__label" for="token"><?php _e('Solo API token', 'woo-solo-api'); ?></label>
<div class="options-wrapper__content">
	<div class="content__subtitle">
		<?php esc_html_e('Enter your personal token that you obtained from your SOLO account.', 'woo-solo-api'); ?>
	</div>
	<label for="apiToken" class="content__label">
		<?php esc_html_e('SOLO API token:', 'woo-solo-api'); ?>
	</label>
	<input
		class="js-api-token"
		type="text"
		id="apiToken"
		name="apiToken"
		value="<?php echo esc_attr($this->token); ?>"
	/>
</div>
