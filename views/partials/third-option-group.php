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
<input class="options-wrapper__input" id="extra" type="radio" name="tabs" />
<label class="options-wrapper__label" for="extra"><?php _e('Additional settings', 'woo-solo-api'); ?></label>
<div class="options-wrapper__content">
	<div class="content">
		<div class="content__item content__item--one-column">
			<div class="content__item-label">
				<h3 class="content__item-label--heading">
					<?php esc_html_e('WooCommerce checkout settings', 'woo-solo-api'); ?>
				</h3>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="enable-pin">
					<?php esc_html_e('Enable the PIN field on the billing and shipping from in the checkout.', 'woo-solo-api'); ?>
				</label>
			</div>
			<div class="content__item-content">
				<input
					class="js-enable-pin"
					type="checkbox"
					id="enable-pin"
					name="enable-pin"
					value="1" <?php checked('1', esc_attr($this->enablePin), true); ?>
				/>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="enable-iban">
					<?php esc_html_e('Enable the IBAN field on the billing and shipping from in the checkout.', 'woo-solo-api'); ?>
				</label>
			</div>
			<div class="content__item-content">
				<input
					class="js-enable-iban"
					type="checkbox"
					id="enable-iban"
					name="enable-iban"
					value="1" <?php checked('1', esc_attr($this->enableIban), true); ?>
				/>
			</div>
		</div>
		<div class="content__item content__item--one-column">
			<div class="content__item-label">
				<h3 class="content__item-label--heading">
					<?php esc_html_e('PDF settings', 'woo-solo-api'); ?>
				</h3>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="send-pdf">
					<?php esc_html_e('Send the email to the client with the PDF of the order or the invoice.', 'woo-solo-api'); ?>
				</label>
			</div>
			<div class="content__item-content">
				<input
					class="js-send-pdf"
					type="checkbox"
					id="send-pdf"
					name="send-pdf"
					value="1" <?php checked('1', esc_attr($this->sendPdf), true); ?>
				/>
			</div>
		</div>
		<div class="content__item content__item--one-column">
			<div class="content__item-label">
				<h3 class="content__item-label--heading">
					<?php esc_html_e('Send mail to selected payment gateways', 'woo-solo-api'); ?>
				</h3>
			</div>
		</div>
		<div class="content__item content__item--one-column">
			<div class="content__item-label content__item-label--notice">
					<?php esc_html_e('Create pdf and send mail to the customer only if these payment gateways are selected.', 'woo-solo-api'); ?>
			</div>
		</div>
		<div class="content__item content__item--one-column">
			<?php
			$checkedGateways = $this->mailGateway;
			foreach ($this->availableGateways as $gatewayKey => $gatewayValue) {
				$isChecked = is_array($checkedGateways) && in_array(esc_attr($gatewayKey), $checkedGateways, true);
				?>

				<div class="content__item">
					<div class="content__item-label">
						<label for="<?php echo esc_attr($gatewayKey); ?>">
							<?php echo esc_html($gatewayValue->title); ?>
						</label>
					</div>
					<div class="content__item-content">
						<input
							type="checkbox"
							class="js-mail-gateway"
							id="<?php echo esc_attr($gatewayKey); ?>"
							name="email-gateway[]"
							value="<?php echo esc_attr($gatewayKey); ?>"
							<?php echo $isChecked ? 'checked' : ''; ?>
						/>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="content__item content__item--one-column">
			<div class="content__item-label">
				<h3 class="content__item-label--heading">
					<?php esc_html_e('PDF send control', 'woo-solo-api'); ?>
				</h3>
			</div>
		</div>
		<div class="content__item content__item--one-column">
			<div class="content__item-label content__item-label--notice">
				<?php
				printf(
					'%1$s <br> %2$s %3$s',
					esc_html__('Decide when to send the PDF of the order or invoice.', 'woo-solo-api'),
					esc_html__('On customer checkout, or when you approve the order in the WooCommerce admin.', 'woo-solo-api'),
					esc_html__('This will determine when the call to the SOLO API will be made.', 'woo-solo-api')
				);
				?>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="pdf-send-control">
					<?php esc_html_e('Send on:', 'woo-solo-api'); ?>
				</label>
			</div>
			<div class="content__item-content">
				<select
					class="js-pdf-send-control content__item-content--select-half"
					name="pdf-send-control"
					id="pdf-send-control"
				>
					<option value="checkout" <?php selected('checkout', $this->sendControl, true); ?>>
						<?php esc_html_e('Checkout', 'woo-solo-api'); ?>
					</option>
					<option value="status_change" <?php selected('status_change', $this->sendControl, true); ?>>
						<?php esc_html_e('Status change to \'completed\'', 'woo-solo-api'); ?>
					</option>
				</select>
			</div>
		</div>
	</div>
</div>

