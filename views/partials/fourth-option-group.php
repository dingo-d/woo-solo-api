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
<input id="email" type="radio" name="tabs" />
<label for="email"><?php _e('Email settings', 'woo-solo-api'); ?></label>
<div class="content">
	<div class="option">
		<label for="email-title" class="subtitle">
			<?php esc_html_e('Set the title of the mail that will be send with the PDF invoice.', 'woo-solo-api'); ?>
		</label>
		<input
			class="js-mail-title"
			type="text"
			id="email-title"
			name="email-title"
			value="<?php echo esc_attr($this->mailTitle); ?>"
		/>
	</div>
	<div class="option">
		<label for="this-message" class="subtitle">
			<?php esc_html_e('Type the message that will appear on the mail with the invoice PDF attached.', 'woo-solo-api'); ?>
		</label>
		<textarea
			class="js-email-message"
			name="email-message"
			id="email-message"
			cols="30"
			rows="10">
			<?php echo wp_kses_post($this->message); ?>
		</textarea>
	</div>
	<div class="option">
		<label for="change-mail-from" class="subtitle">
			<?php esc_html_e('Change the \'from\' name that shows when WordPress sends the mail.', 'woo-solo-api'); ?>
		</label>
		<div class="caution">
			<?php esc_html_e('CAUTION: This change is global, every mail send from your WordPress will have this \'from\' name.', 'woo-solo-api'); ?>
		</div>
		<input
			class="js-email-from"
			type="text"
			id="change-mail-from"
			name="change-mail-from"
			value="<?php echo esc_attr($this->changeMailFrom); ?>"
		/>
	</div>
</div>

