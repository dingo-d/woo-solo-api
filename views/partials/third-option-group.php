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
<input id="extra" type="radio" name="tabs" />
<label for="extra"><?php _e('Additional settings', 'woo-solo-api'); ?></label>
<div class="content">
	<h4><?php esc_html_e('WooCommerce checkout settings', 'woo-solo-api'); ?></h4>
	<div class="option">
		<label for="enable-pin">
			<input
				class="js-enable-pin"
				type="checkbox"
				id="enable-pin"
				name="enable-pin"
				value="1" <?php checked('1', esc_attr($this->enablePin), true); ?>
			/>
			<?php esc_html_e('Enable the PIN field on the billing and shipping from in the checkout.', 'woo-solo-api'); ?>
		</label>
	</div>
	<div class="option">
		<label for="enable-iban">
			<input
				class="js-enable-iban"
				type="checkbox"
				id="enable-iban"
				name="enable-iban"
				value="1" <?php checked('1', esc_attr($this->enableIban), true); ?>
			/>
			<?php esc_html_e('Enable the IBAN field on the billing and shipping from in the checkout.', 'woo-solo-api'); ?>
		</label>
	</div>
	<h4><?php esc_html_e('PDF settings', 'woo-solo-api'); ?></h4>
	<div class="option">
		<label for="send-pdf">
			<input
				class="js-send-pdf"
				type="checkbox"
				id="send-pdf"
				name="send-pdf"
				value="1" <?php checked('1', esc_attr($this->sendPdf), true); ?>
			/>
			<?php esc_html_e('Send the email to the client with the PDF of the order or the invoice.', 'woo-solo-api'); ?>
		</label>
	</div>
	<div class="option">
		<label for="email-gateway[]" class="subtitle">
			<?php esc_html_e('Create pdf and send mail to the customer only if these payment gateways are selected.', 'woo-solo-api'); ?>
		</label>
		<?php
		$checkedGateways = $this->mailGateway;
		foreach ($this->availableGateways as $gatewayKey => $gatewayValue) { ?>
			<label class="multi_checkbox">
				<input
					class="js-mail-gateway"
					name="email-gateway[]"
					name="email-gateway[]"
					value="<?php echo esc_attr($gatewayKey); ?>"
					<?php echo (is_array($checkedGateways) && in_array(esc_attr($gatewayKey), $checkedGateways, true)) ? 'checked' : ''; ?>
				/>
				<?php echo esc_html($gatewayValue->title); ?>
			</label><br>
		<?php } ?>
	</div>
	<div class="option">
		<label for="pdf-send-control" class="subtitle"><?php esc_html_e('PDF send control', 'woo-solo-api'); ?></label>
		<div class="solo-info">
		<?php printf(
			'%1$s <br> %2$s',
			esc_html__('Decide when to send the PDF of the order or invoice - on customer checkout, or when you approve the order in the WooCommerce admin.', 'woo-solo-api'),
			esc_html__('This will determine when the call to the SOLO API will be made.', 'woo-solo-api')
		); ?>
		</div>
		<select class="js-pdf-send-control" name="pdf-send-control" id="pdf-send-control">
			<option value="checkout" <?php selected('checkout', $this->sendControl, true); ?>>
				<?php esc_html_e('On checkout', 'woo-solo-api'); ?>
			</option>
			<option value="status_change" <?php selected('status_change', $this->sendControl, true); ?>>
				<?php esc_html_e('On status change to \'completed\'', 'woo-solo-api'); ?>
			</option>
		</select>
	</div>
</div>

