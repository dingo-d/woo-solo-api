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
<input class="options-wrapper__input" id="options" type="radio" name="tabs" />
<label class="options-wrapper__label" for="options"><?php _e('Solo API options', 'woo-solo-api'); ?></label>
<div class="options-wrapper__content">
	<div class="content content--two-columns">
		<div class="content__item">
			<div class="content__item-label">
				<label for="measure">
					<?php esc_html_e('Unit measure', 'woo-solo-api'); ?>
				</label>
				<p class="content__item-label--notice">
					<?php esc_html_e('Select the default measure in your shop (e.g. piece, hour, m^3 etc.)', 'woo-solo-api'); ?>
				</p>
			</div>
			<div class="content__item-content">
				<select class="js-measure content__item-content--select" name="measure" id="measure">
					<option value="1" <?php selected('1', $this->measure, true); ?>>
						<?php esc_html_e('-', 'woo-solo-api'); ?>
					</option>
					<option value="2" <?php selected('2', $this->measure, true); ?>>
						<?php esc_html_e('piece', 'woo-solo-api'); ?>
					</option>
					<option value="3" <?php selected('3', $this->measure, true); ?>>
						<?php esc_html_e('hour', 'woo-solo-api'); ?>
					</option>
					<option value="4" <?php selected('4', $this->measure, true); ?>>
						<?php esc_html_e('year', 'woo-solo-api'); ?>
					</option>
					<option value="5" <?php selected('5', $this->measure, true); ?>>
						<?php esc_html_e('km', 'woo-solo-api'); ?>
					</option>
					<option value="6" <?php selected('6', $this->measure, true); ?>>
						<?php esc_html_e('litre', 'woo-solo-api'); ?>
					</option>
					<option value="7" <?php selected('7', $this->measure, true); ?>>
						<?php esc_html_e('kg', 'woo-solo-api'); ?>
					</option>
					<option value="8" <?php selected('8', $this->measure, true); ?>>
						<?php esc_html_e('kWh', 'woo-solo-api'); ?>
					</option>
					<option value="9" <?php selected('9', $this->measure, true); ?>>
						<?php esc_html_e('m³', 'woo-solo-api'); ?>
					</option>
					<option value="10" <?php selected('10', $this->measure, true); ?>>
						<?php esc_html_e('tonne', 'woo-solo-api'); ?>
					</option>
					<option value="11" <?php selected('11', $this->measure, true); ?>>
						<?php esc_html_e('m²', 'woo-solo-api'); ?>
					</option>
					<option value="12" <?php selected('12', $this->measure, true); ?>>
						<?php esc_html_e('m', 'woo-solo-api'); ?>
					</option>
					<option value="13" <?php selected('13', $this->measure, true); ?>>
						<?php esc_html_e('day', 'woo-solo-api'); ?>
					</option>
					<option value="14" <?php selected('14', $this->measure, true); ?>>
						<?php esc_html_e('month', 'woo-solo-api'); ?>
					</option>
					<option value="15" <?php selected('15', $this->measure, true); ?>>
						<?php esc_html_e('night', 'woo-solo-api'); ?>
					</option>
					<option value="16" <?php selected('16', $this->measure, true); ?>>
						<?php esc_html_e('card', 'woo-solo-api'); ?>
					</option>
					<option value="17" <?php selected('17', $this->measure, true); ?>>
						<?php esc_html_e('account', 'woo-solo-api'); ?>
					</option>
					<option value="18" <?php selected('18', $this->measure, true); ?>>
						<?php esc_html_e('pair', 'woo-solo-api'); ?>
					</option>
					<option value="19" <?php selected('19', $this->measure, true); ?>>
						<?php esc_html_e('ml', 'woo-solo-api'); ?>
					</option>
					<option value="20" <?php selected('20', $this->measure, true); ?>>
						<?php esc_html_e('pax', 'woo-solo-api'); ?>
					</option>
					<option value="21" <?php selected('21', $this->measure, true); ?>>
						<?php esc_html_e('room', 'woo-solo-api'); ?>
					</option>
					<option value="22" <?php selected('22', $this->measure, true); ?>>
						<?php esc_html_e('appartmant', 'woo-solo-api'); ?>
					</option>
					<option value="23" <?php selected('23', $this->measure, true); ?>>
						<?php esc_html_e('term', 'woo-solo-api'); ?>
					</option>
					<option value="24" <?php selected('24', $this->measure, true); ?>>
						<?php esc_html_e('set', 'woo-solo-api'); ?>
					</option>
					<option value="25" <?php selected('25', $this->measure, true); ?>>
						<?php esc_html_e('package', 'woo-solo-api'); ?>
					</option>
					<option value="26" <?php selected('26', $this->measure, true); ?>>
						<?php esc_html_e('point', 'woo-solo-api'); ?>
					</option>
					<option value="27" <?php selected('27', $this->measure, true); ?>>
						<?php esc_html_e('service', 'woo-solo-api'); ?>
					</option>
					<option value="28" <?php selected('28', $this->measure, true); ?>>
						<?php esc_html_e('pal', 'woo-solo-api'); ?>
					</option>
					<option value="29" <?php selected('29', $this->measure, true); ?>>
						<?php esc_html_e('kont', 'woo-solo-api'); ?>
					</option>
					<option value="30" <?php selected('30', $this->measure, true); ?>>
						<?php esc_html_e('čl', 'woo-solo-api'); ?>
					</option>
					<option value="31" <?php selected('31', $this->measure, true); ?>>
						<?php esc_html_e('tis', 'woo-solo-api'); ?>
					</option>
					<option value="32" <?php selected('32', $this->measure, true); ?>>
						<?php esc_html_e('sec', 'woo-solo-api'); ?>
					</option>
					<option value="33" <?php selected('33', $this->measure, true); ?>>
						<?php esc_html_e('min', 'woo-solo-api'); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="service-type">
					<?php esc_html_e('Enter the type of the service', 'woo-solo-api'); ?>
				</label>
				<p class="content__item-label--notice">
					<?php esc_html_e('You can find it in your account settings (Usluge -> Tipovi usluga). Has to be unique ID of the service.', 'woo-solo-api'); ?>
				</p>
			</div>
			<div class="content__item-content">
				<input
					class="js-service-type content__item-content--input-full"
					type="text"
					id="service-type"
					name="service-type"
					value="<?php echo esc_attr($this->serviceType); ?>"
				/>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="languages"><?php esc_html_e('Languages', 'woo-solo-api'); ?></label>
			</div>
			<div class="content__item-content">
				<select class="js-languages content__item-content--select" name="languages" id="languages">
					<option value="1" <?php selected('1', $this->languages, true); ?>>
						<?php esc_html_e('Croatian', 'woo-solo-api'); ?>
					</option>
					<option value="2" <?php selected('2', $this->languages, true); ?>>
						<?php esc_html_e('English', 'woo-solo-api'); ?>
					</option>
					<option value="3" <?php selected('3', $this->languages, true); ?>>
						<?php esc_html_e('German', 'woo-solo-api'); ?>
					</option>
					<option value="4" <?php selected('4', $this->languages, true); ?>>
						<?php esc_html_e('French', 'woo-solo-api'); ?>
					</option>
					<option value="5" <?php selected('5', $this->languages, true); ?>>
						<?php esc_html_e('Italian', 'woo-solo-api'); ?>
					</option>
					<option value="6" <?php selected('6', $this->languages, true); ?>>
						<?php esc_html_e('Spanish', 'woo-solo-api'); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="show-taxes"><?php esc_html_e('Show taxes', 'woo-solo-api'); ?></label>
			</div>
			<div class="content__item-content">
				<select class="js-show-taxes content__item-content--select" name="show-taxes" id="show-taxes">
					<option value="0" <?php selected('0', $this->showTaxes, true); ?>>
						<?php esc_html_e('Don\'t show tax', 'woo-solo-api'); ?>
					</option>
					<option value="1" <?php selected('1', $this->showTaxes, true); ?>>
						<?php esc_html_e('Show tax', 'woo-solo-api'); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="currency"><?php esc_html_e('Currency', 'woo-solo-api'); ?></label>
			</div>
			<div class="content__item-content">
				<select class="je-currency content__item-content--select" name="currency" id="currency">
					<option value="1" <?php selected('1', $this->currency, true); ?>>
						<?php esc_html_e('HRK - kn', 'woo-solo-api'); ?>
					</option>
					<option value="2" <?php selected('2', $this->currency, true); ?>>
						<?php esc_html_e('AUD - $', 'woo-solo-api'); ?>
					</option>
					<option value="3" <?php selected('3', $this->currency, true); ?>>
						<?php esc_html_e('CAD - $', 'woo-solo-api'); ?>
					</option>
					<option value="4" <?php selected('4', $this->currency, true); ?>>
						<?php esc_html_e('CZK - Kč', 'woo-solo-api'); ?>
					</option>
					<option value="5" <?php selected('5', $this->currency, true); ?>>
						<?php esc_html_e('DKK - kr', 'woo-solo-api'); ?>
					</option>
					<option value="6" <?php selected('6', $this->currency, true); ?>>
						<?php esc_html_e('HUF - Ft', 'woo-solo-api'); ?>
					</option>
					<option value="7" <?php selected('7', $this->currency, true); ?>>
						<?php esc_html_e('JPY - ¥', 'woo-solo-api'); ?>
					</option>
					<option value="8" <?php selected('8', $this->currency, true); ?>>
						<?php esc_html_e('NOK - kr', 'woo-solo-api'); ?>
					</option>
					<option value="9" <?php selected('9', $this->currency, true); ?>>
						<?php esc_html_e('SEK - kr', 'woo-solo-api'); ?>
					</option>
					<option value="10" <?php selected('10', $this->currency, true); ?>>
						<?php esc_html_e('CHF - CHF', 'woo-solo-api'); ?>
					</option>
					<option value="11" <?php selected('11', $this->currency, true); ?>>
						<?php esc_html_e('GBP - £', 'woo-solo-api'); ?>
					</option>
					<option value="12" <?php selected('12', $this->currency, true); ?>>
						<?php esc_html_e('USD - $', 'woo-solo-api'); ?>
					</option>
					<option value="13" <?php selected('13', $this->currency, true); ?>>
						<?php esc_html_e('BAM - KM', 'woo-solo-api'); ?>
					</option>
					<option value="14" <?php selected('14', $this->currency, true); ?>>
						<?php esc_html_e('EUR - €', 'woo-solo-api'); ?>
					</option>
					<option value="15" <?php selected('15', $this->currency, true); ?>>
						<?php esc_html_e('PLN - zł', 'woo-solo-api'); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<label for="invoice-type" class="content__label">
					<?php esc_html_e('Type of invoice', 'woo-solo-api'); ?>
				</label>
				<p class="content__item-label--notice">
					<?php esc_html_e('Only works with invoice, not with offer', 'woo-solo-api'); ?>
				</p>
			</div>
			<div class="content__item-content">
				<select class="js-invoice-type content__item-content--select" name="invoice-type" id="invoice-type">
					<option value="1" <?php selected('1', $this->invoiceType, true); ?>>
						<?php esc_html_e('R', 'woo-solo-api'); ?>
					</option>
					<option value="2" <?php selected('2', $this->invoiceType, true); ?>>
						<?php esc_html_e('R1', 'woo-solo-api'); ?>
					</option>
					<option value="3" <?php selected('3', $this->invoiceType, true); ?>>
						<?php esc_html_e('R2', 'woo-solo-api'); ?>
					</option>
					<option value="4" <?php selected('4', $this->invoiceType, true); ?>>
						<?php esc_html_e('No label', 'woo-solo-api'); ?>
					</option>
					<option value="5" <?php selected('5', $this->invoiceType, true); ?>>
						<?php esc_html_e('In advance', 'woo-solo-api'); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="content__item content__item--one-column">
			<div class="content__item-label">
				<?php esc_html_e('Choose the type of payment for each enabled payment gateway', 'woo-solo-api'); ?>
			</div>
		</div>
		<div class="content__item content_item--one-column">
			<div class="content__item-label">
				<label for="due-date" class="content__label">
					<?php esc_html_e('Invoice/Offer due date', 'woo-solo-api'); ?>
				</label>
			</div>
			<div class="content__item-content">
				<select class="js-due-date content__item-content--select" name="due-date" id="due-date">
					<option value="1d" <?php selected('1d', $this->dueDate, true); ?>>
						<?php esc_html_e('1 day', 'woo-solo-api'); ?>
					</option>
					<option value="2d" <?php selected('2d', $this->dueDate, true); ?>>
						<?php esc_html_e('2 days', 'woo-solo-api'); ?>
					</option>
					<option value="3d" <?php selected('3d', $this->dueDate, true); ?>>
						<?php esc_html_e('3 days', 'woo-solo-api'); ?>
					</option>
					<option value="4d" <?php selected('4d', $this->dueDate, true); ?>>
						<?php esc_html_e('4 days', 'woo-solo-api'); ?>
					</option>
					<option value="5d" <?php selected('5d', $this->dueDate, true); ?>>
						<?php esc_html_e('5 days', 'woo-solo-api'); ?>
					</option>
					<option value="6d" <?php selected('6d', $this->dueDate, true); ?>>
						<?php esc_html_e('6 days', 'woo-solo-api'); ?>
					</option>
					<option value="1" <?php selected('1', $this->dueDate, true); ?>>
						<?php esc_html_e('1 week', 'woo-solo-api'); ?>
					</option>
					<option value="2" <?php selected('2', $this->dueDate, true); ?>>
						<?php esc_html_e('2 weeks', 'woo-solo-api'); ?>
					</option>
					<option value="3" <?php selected('3', $this->dueDate, true); ?>>
						<?php esc_html_e('3 weeks', 'woo-solo-api'); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="content__item content__item--one-column">
			<?php
			foreach ($this->availableGateways as $gatewayType => $gatewayOptions) {
				$gatewayID = $gatewayOptions->id;
				$gatewayTitle = $gatewayOptions->title;

				$offer = "billOffer{$gatewayID}";
				$fiscal = "fiscalization{$gatewayID}";
				$payment = "paymentType{$gatewayID}";

				$billOffer = $this->$offer;
				$fiscalization = $this->$fiscal;
				$paymentType = $this->$payment;
				?>
				<div class="content__item content__item--one-column">
					<div class="content__item-label">
						<h3 class="content__item-label--heading"><?php echo esc_html($gatewayTitle); ?></h3>
					</div>
				</div>
				<div class="content__item">
					<div class="content__item-label">
						<label for="bill-offer-<?php echo esc_html($gatewayID); ?>">
							<?php esc_html_e('Type of payment document', 'woo-solo-api'); ?>
						</label>
					</div>
					<div class="content__item-content">
						<input
							class="js-payment-gateway"
							type="hidden"
							name="payment-gateway[]"
							value="<?php echo esc_html($gatewayID); ?>"
						/>
						<select
							class="js-bill-offer content__item-content--select"
							name="bill-offer-<?php echo esc_attr($gatewayID); ?>"
							id="bill-offer-<?php echo esc_html($gatewayID); ?>"
						>
							<option value="ponuda" <?php selected('ponuda', $billOffer, true); ?>>
								<?php esc_html_e('Offer', 'woo-solo-api'); ?>
							</option>
							<option value="racun" <?php selected('racun', $billOffer, true); ?>>
								<?php esc_html_e('Invoice', 'woo-solo-api'); ?>
							</option>
						</select>
					</div>
				</div>
				<div class="content__item">
					<div class="content__item-label">
						<label for="payment-type-<?php echo esc_attr($gatewayID); ?>">
							<?php esc_html_e('Payment option types', 'woo-solo-api'); ?>
						</label>
					</div>
					<div class="content__item-content">
						<select
							class="js-payment-type content__item-content--select"
							name="payment-type-<?php echo esc_attr($gatewayID); ?>"
							id="payment-type-<?php echo esc_attr($gatewayID); ?>"
						>
							<option value="1" <?php selected('1', $paymentType, true); ?>>
								<?php esc_html_e('Transactional account', 'woo-solo-api'); ?>
							</option>
							<option value="2" <?php selected('2', $paymentType, true); ?>>
								<?php esc_html_e('Cash', 'woo-solo-api'); ?>
							</option>
							<option value="3" <?php selected('3', $paymentType, true); ?>>
								<?php esc_html_e('Cards', 'woo-solo-api'); ?>
							</option>
							<option value="4" <?php selected('4', $paymentType, true); ?>>
								<?php esc_html_e('Cheque', 'woo-solo-api'); ?>
							</option>
							<option value="5" <?php selected('5', $paymentType, true); ?>>
								<?php esc_html_e('Other', 'woo-solo-api'); ?>
							</option>
						</select>
					</div>
				</div>
				<div class="content__item content__item--has-bottom-border">
					<div class="content__item-label">
						<label for="fiscalization-<?php echo esc_html($gatewayID); ?>">
							<?php esc_html_e('Check if you want the invoice to be fiscalized.*', 'woo-solo-api'); ?>
						</label>
					</div>
					<div class="content__item-content">
						<input
							class="js-fiscalization"
							type="checkbox"
							id="fiscalization-<?php echo esc_html($gatewayID); ?>"
							name="fiscalization-<?php echo esc_attr($gatewayID); ?>"
							value="fiscalization-<?php echo esc_attr($gatewayID); ?>"
							<?php checked('fiscal-' . esc_attr($gatewayID), esc_attr($fiscalization), true); ?>
						/>
					</div>
				</div>
				<hr>
				<?php
			}
			?>
		</div>
		<div class="content__item content__item--one-column">
			<div class="content__item-label content__item-label--notice">
				<?php printf(
					'%s <a href="https://www.hnb.hr/temeljne-funkcije/monetarna-politika/tecajna-lista/tecajna-lista" target="_blank" rel="noopener noreferrer">%s</a>. %s %s',
					esc_html__('You can check the currency rate at', 'woo-solo-api'),
					esc_html__('Croatian National Bank', 'woo-solo-api'),
					esc_html__('The currency will be automatically added if the selected currency is different from HRK.', 'woo-solo-api'),
					esc_html__('Also, a note about conversion rate will be added to the invoice/offer.', 'woo-solo-api')
				); ?>
			</div>
		</div>
		<div class="content__item content__item--one-column">
			<div class="content__item-label content__item-label--notice">
				<?php esc_html_e('* Fiscalization certificate must be added in the SOLO options, and it only applies to invoices.', 'woo-solo-api'); ?>
			</div>
		</div>
	</div>
</div>
