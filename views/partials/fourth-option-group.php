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
<input class="options-wrapper__input" id="email" type="radio" name="tabs" />
<label class="options-wrapper__label" for="email"><?php _e('Email settings', 'woo-solo-api'); ?></label>
<div class="options-wrapper__content">
	<div class="content">
		<div class="content__item">
			<div class="content__item-label">
				<label for="email-title">
					<?php esc_html_e('Set the title of the mail that will be send with the PDF invoice.', 'woo-solo-api'); ?>
				</label>
			</div>
			<div class="content__item-content">
				<input
					class="js-mail-title content__item-content--input-full"
					type="text"
					id="email-title"
					name="email-title"
					value="<?php echo esc_attr($this->mailTitle); ?>"
				/>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="email-message">
					<?php esc_html_e('Type the message that will appear on the mail with the invoice PDF attached.', 'woo-solo-api'); ?>
				</label>
			</div>
			<div class="content__item-content">
				<textarea
					class="js-email-message content__item-content--input-full"
					name="email-message"
					id="email-message"
					cols="30"
					rows="10">
					<?php echo wp_kses_post($this->message); ?>
				</textarea>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="change-mail-from">
					<?php esc_html_e('Change the \'from\' name that shows when WordPress sends the mail.', 'woo-solo-api'); ?>
				</label>
				<p class="content__item-label--notice">
					<strong><?php esc_html_e('CAUTION:', 'woo-solo-api'); ?></strong>
					<?php esc_html_e('This change is global, every mail send from your WordPress will have this \'from\' name.', 'woo-solo-api'); ?>
				</p>
			</div>
			<div class="content__item-content">
				<input
					class="js-email-from content__item-content--input-full"
					type="text"
					id="change-mail-from"
					name="change-mail-from"
					value="<?php echo esc_attr($this->changeMailFrom); ?>"
				/>
			</div>
		</div>
	</div>
</div>

