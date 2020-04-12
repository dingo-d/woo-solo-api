<?php

namespace MadeByDenis\WooSoloApi\Views;

?>
<div class="wrap">
	<h2><?php echo esc_html($this->pageTitle); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'solo-api-settings-group' ); ?>
		<?php do_settings_sections( 'solo-api-settings-group' ); ?>
		<div class="notice plugin-notice"><?php esc_html_e( 'For more details on the options you can read the official SOLO API documentation here: ', 'woo-solo-api' ); ?><a href="<?php echo esc_url( 'https://solo.com.hr/api-dokumentacija' ); ?>"><?php echo esc_html( 'https://solo.com.hr/api-dokumentacija' ); ?></a></div>
		<div class="options-wrapper">
			<div class="options-wrapper__tabs">
				<div href="#tab1" class="tab active"><?php esc_html_e( 'Solo API token', 'woo-solo-api' ); ?></div>
				<div href="#tab2" class="tab"><?php esc_html_e( 'Solo API options', 'woo-solo-api' ); ?></div>
				<div href="#tab3" class="tab"><?php esc_html_e( 'Additional settings', 'woo-solo-api' ); ?></div>
				<div href="#tab4" class="tab"><?php esc_html_e( 'Mail settings', 'woo-solo-api' ); ?></div>
				<div href="#tab5" class="tab"><?php esc_html_e( 'Solo API test', 'woo-solo-api' ); ?></div>
			</div>
			<div class="options-wrapper__content">
				<div id="tab1" class="tab-content active">
					<div class="option">
						<label for="this-token" class="subtitle"><?php esc_html_e( 'Enter your personal token that you obtained from you SOLO account.', 'woo-solo-api' ); ?></label>
						<input type="text" id="this-token" name="this-token" value="<?php echo esc_attr( $this->token ); ?>" />
					</div>
				</div>
				<div id="tab2" class="tab-content">
					<div class="left">
						<div class="option">
							<label for="this-measure" class="subtitle"><?php esc_html_e( 'Unit measure - select the default measure in your shop (e.g. piece, hour, m^3 etc.)', 'woo-solo-api' ); ?></label>
							<select name="this-measure" id="this-measure">
								<option value="1" <?php selected( '1', $this->measure, true ); ?>><?php esc_html_e( '-', 'woo-solo-api' ); ?></option>
								<option value="2" <?php selected( '2', $this->measure, true ); ?>><?php esc_html_e( 'piece', 'woo-solo-api' ); ?></option>
								<option value="3" <?php selected( '3', $this->measure, true ); ?>><?php esc_html_e( 'hour', 'woo-solo-api' ); ?></option>
								<option value="4" <?php selected( '4', $this->measure, true ); ?>><?php esc_html_e( 'year', 'woo-solo-api' ); ?></option>
								<option value="5" <?php selected( '5', $this->measure, true ); ?>><?php esc_html_e( 'km', 'woo-solo-api' ); ?></option>
								<option value="6" <?php selected( '6', $this->measure, true ); ?>><?php esc_html_e( 'litre', 'woo-solo-api' ); ?></option>
								<option value="7" <?php selected( '7', $this->measure, true ); ?>><?php esc_html_e( 'kg', 'woo-solo-api' ); ?></option>
								<option value="8" <?php selected( '8', $this->measure, true ); ?>><?php esc_html_e( 'kWh', 'woo-solo-api' ); ?></option>
								<option value="9" <?php selected( '9', $this->measure, true ); ?>><?php esc_html_e( 'm³', 'woo-solo-api' ); ?></option>
								<option value="10" <?php selected( '10', $this->measure, true ); ?>><?php esc_html_e( 'tonne', 'woo-solo-api' ); ?></option>
								<option value="11" <?php selected( '11', $this->measure, true ); ?>><?php esc_html_e( 'm²', 'woo-solo-api' ); ?></option>
								<option value="12" <?php selected( '12', $this->measure, true ); ?>><?php esc_html_e( 'm', 'woo-solo-api' ); ?></option>
								<option value="13" <?php selected( '13', $this->measure, true ); ?>><?php esc_html_e( 'day', 'woo-solo-api' ); ?></option>
								<option value="14" <?php selected( '14', $this->measure, true ); ?>><?php esc_html_e( 'month', 'woo-solo-api' ); ?></option>
								<option value="15" <?php selected( '15', $this->measure, true ); ?>><?php esc_html_e( 'night', 'woo-solo-api' ); ?></option>
								<option value="16" <?php selected( '16', $this->measure, true ); ?>><?php esc_html_e( 'card', 'woo-solo-api' ); ?></option>
								<option value="17" <?php selected( '17', $this->measure, true ); ?>><?php esc_html_e( 'account', 'woo-solo-api' ); ?></option>
								<option value="18" <?php selected( '18', $this->measure, true ); ?>><?php esc_html_e( 'pair', 'woo-solo-api' ); ?></option>
								<option value="19" <?php selected( '19', $this->measure, true ); ?>><?php esc_html_e( 'ml', 'woo-solo-api' ); ?></option>
								<option value="20" <?php selected( '20', $this->measure, true ); ?>><?php esc_html_e( 'pax', 'woo-solo-api' ); ?></option>
								<option value="21" <?php selected( '21', $this->measure, true ); ?>><?php esc_html_e( 'room', 'woo-solo-api' ); ?></option>
								<option value="22" <?php selected( '22', $this->measure, true ); ?>><?php esc_html_e( 'appartmant', 'woo-solo-api' ); ?></option>
								<option value="23" <?php selected( '23', $this->measure, true ); ?>><?php esc_html_e( 'term', 'woo-solo-api' ); ?></option>
								<option value="24" <?php selected( '24', $this->measure, true ); ?>><?php esc_html_e( 'set', 'woo-solo-api' ); ?></option>
								<option value="25" <?php selected( '25', $this->measure, true ); ?>><?php esc_html_e( 'package', 'woo-solo-api' ); ?></option>
								<option value="26" <?php selected( '26', $this->measure, true ); ?>><?php esc_html_e( 'point', 'woo-solo-api' ); ?></option>
								<option value="27" <?php selected( '27', $this->measure, true ); ?>><?php esc_html_e( 'service', 'woo-solo-api' ); ?></option>
								<option value="28" <?php selected( '28', $this->measure, true ); ?>><?php esc_html_e( 'pal', 'woo-solo-api' ); ?></option>
								<option value="29" <?php selected( '29', $this->measure, true ); ?>><?php esc_html_e( 'kont', 'woo-solo-api' ); ?></option>
								<option value="30" <?php selected( '30', $this->measure, true ); ?>><?php esc_html_e( 'čl', 'woo-solo-api' ); ?></option>
								<option value="31" <?php selected( '31', $this->measure, true ); ?>><?php esc_html_e( 'tis', 'woo-solo-api' ); ?></option>
								<option value="32" <?php selected( '32', $this->measure, true ); ?>><?php esc_html_e( 'sec', 'woo-solo-api' ); ?></option>
								<option value="33" <?php selected( '33', $this->measure, true ); ?>><?php esc_html_e( 'min', 'woo-solo-api' ); ?></option>
							</select>
						</div>
						<div class="option">
							<label for="this-languages" class="subtitle"><?php esc_html_e( 'Languages', 'woo-solo-api' ); ?></label>
							<select name="this-languages" id="this-languages">
								<option value="1" <?php selected( '1', $this->languages, true ); ?>><?php esc_html_e( 'Croatian', 'woo-solo-api' ); ?></option>
								<option value="2" <?php selected( '2', $this->languages, true ); ?>><?php esc_html_e( 'English', 'woo-solo-api' ); ?></option>
								<option value="3" <?php selected( '3', $this->languages, true ); ?>><?php esc_html_e( 'German', 'woo-solo-api' ); ?></option>
								<option value="4" <?php selected( '4', $this->languages, true ); ?>><?php esc_html_e( 'French', 'woo-solo-api' ); ?></option>
								<option value="5" <?php selected( '5', $this->languages, true ); ?>><?php esc_html_e( 'Italian', 'woo-solo-api' ); ?></option>
								<option value="6" <?php selected( '6', $this->languages, true ); ?>><?php esc_html_e( 'Spanish', 'woo-solo-api' ); ?></option>
							</select>
						</div>
						<div class="option">
							<label for="this-currency" class="subtitle"><?php esc_html_e( 'Currency', 'woo-solo-api' ); ?></label>
							<select name="this-currency" id="this-currency">
								<option value="1" <?php selected( '1', $this->currency, true ); ?>><?php esc_html_e( 'HRK - kn', 'woo-solo-api' ); ?></option>
								<option value="2" <?php selected( '2', $this->currency, true ); ?>><?php esc_html_e( 'AUD - $', 'woo-solo-api' ); ?></option>
								<option value="3" <?php selected( '3', $this->currency, true ); ?>><?php esc_html_e( 'CAD - $', 'woo-solo-api' ); ?></option>
								<option value="4" <?php selected( '4', $this->currency, true ); ?>><?php esc_html_e( 'CZK - Kč', 'woo-solo-api' ); ?></option>
								<option value="5" <?php selected( '5', $this->currency, true ); ?>><?php esc_html_e( 'DKK - kr', 'woo-solo-api' ); ?></option>
								<option value="6" <?php selected( '6', $this->currency, true ); ?>><?php esc_html_e( 'HUF - Ft', 'woo-solo-api' ); ?></option>
								<option value="7" <?php selected( '7', $this->currency, true ); ?>><?php esc_html_e( 'JPY - ¥', 'woo-solo-api' ); ?></option>
								<option value="8" <?php selected( '8', $this->currency, true ); ?>><?php esc_html_e( 'NOK - kr', 'woo-solo-api' ); ?></option>
								<option value="9" <?php selected( '9', $this->currency, true ); ?>><?php esc_html_e( 'SEK - kr', 'woo-solo-api' ); ?></option>
								<option value="10" <?php selected( '10', $this->currency, true ); ?>><?php esc_html_e( 'CHF - CHF', 'woo-solo-api' ); ?></option>
								<option value="11" <?php selected( '11', $this->currency, true ); ?>><?php esc_html_e( 'GBP - £', 'woo-solo-api' ); ?></option>
								<option value="12" <?php selected( '12', $this->currency, true ); ?>><?php esc_html_e( 'USD - $', 'woo-solo-api' ); ?></option>
								<option value="13" <?php selected( '13', $this->currency, true ); ?>><?php esc_html_e( 'BAM - KM', 'woo-solo-api' ); ?></option>
								<option value="14" <?php selected( '14', $this->currency, true ); ?>><?php esc_html_e( 'EUR - €', 'woo-solo-api' ); ?></option>
								<option value="15" <?php selected( '15', $this->currency, true ); ?>><?php esc_html_e( 'PLN - zł', 'woo-solo-api' ); ?></option>
							</select>
						</div>
						<div class="option">
							<div class="subtitle"><?php esc_html_e( 'Choose the type of payment for each enabled payment gateway', 'woo-solo-api' ); ?></div>
							<div class="fields">
								<?php
								foreach ($this->availableGateways as $gatewayType => $gatewayOptions) {
									$gatewayID = $gatewayOptions->id;
									$gatewayTitle = $gatewayOptions->title;

									$offer = "billOffer{$gatewayID}";
									$fiscal = "fiscalization{$gatewayID}";
									$payment = "paymentType{$gatewayID}";

									$billOffer    = $this->$offer;
									$fiscalization = $this->$fiscal;
									$paymentType  = $this->$payment;
									?>
									<div class="fields__single">
										<div class="fields__subtitle fields__subtitle--main"><?php echo esc_html( $gatewayTitle ); ?></div>
										<div class="fields__single--part">
											<input type="hidden" name="this-payment_gateway[]" value="<?php echo esc_html( $gatewayID ); ?>">
											<label for="offer-<?php echo esc_html( $gatewayID ); ?>" class="fields__subtitle"><?php esc_html_e( 'Type of payment document', 'woo-solo-api' ); ?></label>
											<select name="this-bill_offer-<?php echo esc_attr( $gatewayID ); ?>" id="offer-<?php echo esc_html( $gatewayID ); ?>">
												<option value="ponuda" <?php selected( 'ponuda', $billOffer, true ); ?>><?php esc_html_e( 'Offer', 'woo-solo-api' ); ?></option>
												<option value="racun" <?php selected( 'racun', $billOffer, true ); ?>><?php esc_html_e( 'Invoice', 'woo-solo-api' ); ?></option>
											</select>
										</div>
										<div class="fields__single--part">
											<label for="this-payment_type-<?php echo esc_attr( $gatewayID ); ?>" class="fields__subtitle"><?php esc_html_e( 'Payment option types', 'woo-solo-api' ); ?></label>
											<select name="this-payment_type-<?php echo esc_attr( $gatewayID ); ?>" id="this-payment_type-<?php echo esc_attr( $gatewayID ); ?>">
												<option value="1" <?php selected( '1', $paymentType, true ); ?>><?php esc_html_e( 'Transactional account', 'woo-solo-api' ); ?></option>
												<option value="2" <?php selected( '2', $paymentType, true ); ?>><?php esc_html_e( 'Cash', 'woo-solo-api' ); ?></option>
												<option value="3" <?php selected( '3', $paymentType, true ); ?>><?php esc_html_e( 'Cards', 'woo-solo-api' ); ?></option>
												<option value="4" <?php selected( '4', $paymentType, true ); ?>><?php esc_html_e( 'Cheque', 'woo-solo-api' ); ?></option>
												<option value="5" <?php selected( '5', $paymentType, true ); ?>><?php esc_html_e( 'Other', 'woo-solo-api' ); ?></option>
											</select>
										</div>
										<div class="fields__single--part">
											<label for="fiscal-<?php echo esc_html( $gatewayID ); ?>" class="fields__subtitle">
												<input type="checkbox" id="fiscal-<?php echo esc_html( $gatewayID ); ?>"
													   name="this-fiscalization-<?php echo esc_attr( $gatewayID ); ?>"
													   value="fiscal-<?php echo esc_attr( $gatewayID ); ?>"
													<?php checked( 'fiscal-' . esc_attr( $gatewayID ), esc_attr( $fiscalization ), true ); ?>>
												<?php esc_html_e( 'Check if you want the invoice to be fiscalized.*', 'woo-solo-api' ); ?>
											</label>
										</div>
									</div>
									<?php
								}
								?>
							</div>
							<div class="fields__note"><?php esc_html_e( '* Fiscalization certificate must be added in the SOLO options, and it only applies to invoices.', 'woo-solo-api' ); ?></div>
						</div>
					</div>
					<div class="right">
						<div class="option">
							<label for="this-service_type"
								   class="subtitle"><?php esc_html_e( 'Enter the type of the service - you can find it in your account settings (Usluge -> Tipovi usluga). Has to be unique ID of the service.', 'woo-solo-api' ); ?></label>
							<input type="text"
								   id="this-service_type" name="this-service_type" value="<?php echo esc_attr( $this->serviceType ); ?>">
						</div>
						<div class="option">
							<label for="this-show_taxes" class="subtitle"><?php esc_html_e( 'Show taxes', 'woo-solo-api' ); ?></label>
							<select name="this-show_taxes" id="this-show_taxes">
								<option value="0" <?php selected( '0', $this->showTaxes, true ); ?>><?php esc_html_e( 'Don\'t show tax', 'woo-solo-api' ); ?></option>
								<option value="1" <?php selected( '1', $this->showTaxes, true ); ?>><?php esc_html_e( 'Show tax', 'woo-solo-api' ); ?></option>
							</select>
						</div>
						<div class="option">
							<label for="this-invoice_type" class="subtitle"><?php esc_html_e( 'Type of reciepe (only works with reciepe, not with offer)', 'woo-solo-api' ); ?></label>
							<select name="this-invoice_type" id="this-invoice_type">
								<option value="1" <?php selected( '1', $this->invoiceType, true ); ?>><?php esc_html_e( 'R', 'woo-solo-api' ); ?></option>
								<option value="2" <?php selected( '2', $this->invoiceType, true ); ?>><?php esc_html_e( 'R1', 'woo-solo-api' ); ?></option>
								<option value="3" <?php selected( '3', $this->invoiceType, true ); ?>><?php esc_html_e( 'R2', 'woo-solo-api' ); ?></option>
								<option value="4" <?php selected( '4', $this->invoiceType, true ); ?>><?php esc_html_e( 'No label', 'woo-solo-api' ); ?></option>
								<option value="5" <?php selected( '5', $this->invoiceType, true ); ?>><?php esc_html_e( 'In advance', 'woo-solo-api' ); ?></option>
							</select>
						</div>
						<div class="option">
							<label for="this-due_date" class="subtitle"><?php esc_html_e( 'Invoice/Offer due date', 'woo-solo-api' ); ?></label>
							<select name="this-due_date" id="this-due_date">
								<option value="1d" <?php selected( '1d', $this->dueDate, true ); ?>><?php esc_html_e( '1 day', 'woo-solo-api' ); ?></option>
								<option value="2d" <?php selected( '2d', $this->dueDate, true ); ?>><?php esc_html_e( '2 days', 'woo-solo-api' ); ?></option>
								<option value="3d" <?php selected( '3d', $this->dueDate, true ); ?>><?php esc_html_e( '3 days', 'woo-solo-api' ); ?></option>
								<option value="4d" <?php selected( '4d', $this->dueDate, true ); ?>><?php esc_html_e( '4 days', 'woo-solo-api' ); ?></option>
								<option value="5d" <?php selected( '5d', $this->dueDate, true ); ?>><?php esc_html_e( '5 days', 'woo-solo-api' ); ?></option>
								<option value="6d" <?php selected( '6d', $this->dueDate, true ); ?>><?php esc_html_e( '6 days', 'woo-solo-api' ); ?></option>
								<option value="1" <?php selected( '1', $this->dueDate, true ); ?>><?php esc_html_e( '1 week', 'woo-solo-api' ); ?></option>
								<option value="2" <?php selected( '2', $this->dueDate, true ); ?>><?php esc_html_e( '2 weeks', 'woo-solo-api' ); ?></option>
								<option value="3" <?php selected( '3', $this->dueDate, true ); ?>><?php esc_html_e( '3 weeks', 'woo-solo-api' ); ?></option>
							</select>
						</div>
						<div class="option">
							<div class="subtitle"><?php printf( '%s <a href="https://www.hnb.hr/temeljne-funkcije/monetarna-politika/tecajna-lista/tecajna-lista" target="_blank" rel="noopener noreferrer">%s</a>. %s', esc_html__( 'You can check the currency rate at', 'woo-solo-api' ), esc_html__( 'Croatian National Bank', 'woo-solo-api' ), esc_html__( 'The currency will be automatically added if the selected currency is different from HRK. Also a note about conversion rate will be added to the invoice/offer.', 'woo-solo-api' ) ); ?></div>
						</div>
					</div>
				</div>
				<div id="tab3" class="tab-content">
					<h4><?php esc_html_e( 'WooCommerce checkout settings', 'woo-solo-api' ); ?></h4>
					<div class="option">
						<label for="this-enable_pin"><input type="checkbox" id="this-enable_pin" name="this-enable_pin" value="1" <?php checked( '1', esc_attr( $this->enablePin ), true ); ?>><?php esc_html_e( 'Enable the PIN field on the billing and shipping from in the checkout.', 'woo-solo-api' ); ?></label>
					</div>
					<div class="option">
						<label for="this-enable_iban"><input type="checkbox" id="this-enable_iban" name="this-enable_iban" value="1" <?php checked( '1', esc_attr( $this->enableIban ), true ); ?>><?php esc_html_e( 'Enable the IBAN field on the billing and shipping from in the checkout.', 'woo-solo-api' ); ?></label>
					</div>
					<h4><?php esc_html_e( 'PDF settings', 'woo-solo-api' ); ?></h4>
					<div class="option">
						<label for="this-send_pdf"><input type="checkbox" id="this-send_pdf" name="this-send_pdf" value="1" <?php checked( '1', esc_attr( $this->sendPdf ), true ); ?>><?php esc_html_e( 'Send the email to the client with the PDF of the order or the invoice.', 'woo-solo-api' ); ?></label>
					</div>
					<div class="option">
						<label for="this-mail_gateway[]" class="subtitle"><?php esc_html_e( 'Create pdf and send mail to the customer only if these payment gateways are selected.', 'woo-solo-api' ); ?></label>
						<?php
						$checkedGateways = $this->mailGateway;
						foreach ( $this->availableGateways as $gatewayKey => $gatewayValue ) {
							?>
							<label class="multi_checkbox"><input type="checkbox" name="this-mail_gateway[]" value="<?php echo esc_attr( $gatewayKey ); ?>" <?php echo ( is_array( $checkedGateways ) && in_array( esc_attr( $gatewayKey ), $checkedGateways, true ) ) ? 'checked' : ''; ?> ><?php echo esc_html( $gatewayValue->title ); ?></label><br>
							<?php
						}
						?>
					</div>
					<div class="option">
						<label for="this-send_control" class="subtitle"><?php esc_html_e( 'PDF send control', 'woo-solo-api' ); ?></label>
						<div class="solo-info"><?php printf( '%1$s <br> %2$s', esc_html__( 'Decide when to send the PDF of the order or invoice - on customer checkout, or when you approve the order in the WooCommerce admin.', 'woo-solo-api' ), esc_html__( 'This will determine when the call to the SOLO API will be made.', 'woo-solo-api' ) ); ?></div>
						<select name="this-send_control" id="this-send_control">
							<option value="checkout" <?php selected( 'checkout', $this->sendControl, true ); ?>><?php esc_html_e( 'On checkout', 'woo-solo-api' ); ?></option>
							<option value="status_change" <?php selected( 'status_change', $this->sendControl, true ); ?>><?php esc_html_e( 'On status change to \'completed\'', 'woo-solo-api' ); ?></option>
						</select>
					</div>
				</div>
				<div id="tab4" class="tab-content">
					<div class="option">
						<label for="this-mail_title" class="subtitle"><?php esc_html_e( 'Set the title of the mail that will be send with the PDF invoice.', 'woo-solo-api' ); ?></label>
						<input type="text" id="this-mail_title" name="this-mail_title" value="<?php echo esc_attr( $this->mailTitle ); ?>">
					</div>
					<div class="option">
						<label for="this-message" class="subtitle"><?php esc_html_e( 'Type the message that will appear on the mail with the invoice PDF attached.', 'woo-solo-api' ); ?></label>
						<textarea name="this-message" id="this-message" cols="30" rows="10"><?php echo wp_kses_post( $this->message ); ?></textarea>
					</div>
					<div class="option">
						<label for="this-change_mail_from" class="subtitle"><?php esc_html_e( 'Change the \'from\' name that shows when WordPress sends the mail.', 'woo-solo-api' ); ?></label>
						<div class="caution"><?php esc_html_e( 'CAUTION: This change is global, every mail send from your WordPress will have this \'from\' name.', 'woo-solo-api' ); ?></div>
						<input type="text" id="this-change_mail_from" name="this-change_mail_from" value="<?php echo esc_attr( $this->changeMailFrom ); ?>">
					</div>
				</div>
				<div id="tab5" class="tab-content">
					<div class="option">
						<h4><?php esc_html_e( 'This serves for testing purposes only', 'woo-solo-api' ); ?></h4>
						<p><?php esc_html_e( 'Pressing the button will make a request to your Solo API account and will list all the reciepes you have' ); ?></p>
						<button class="button button-primary js-solo-api-send"><?php esc_html_e( 'Make a request', 'woo-solo-api' ); ?></button>
						<pre class="js-solo-api-request" readonly></pre>
					</div>
				</div>
			</div>
		</div>
		<?php submit_button(); ?>
	</form>
</div>
