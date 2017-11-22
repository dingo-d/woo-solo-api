<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://madebydenis.com
 * @since      1.0.0
 *
 * @package    Woo_Solo_Api
 */

?>
<div class="wrap solo-api-options">
  <h1><?php esc_html_e( 'Solo API settings', 'woo-solo-api' ); ?></h1>
  <form method="post" action="options.php">
    <?php settings_fields( 'solo-api-settings-group' ); ?>
    <?php do_settings_sections( 'solo-api-settings-group' ); ?>
    <div class="notice"><?php esc_html_e( 'For more details on the options you can read the official SOLO API documentation here: ', 'woo-solo-api' ); ?><a href="<?php echo esc_url( 'https://solo.com.hr/api-dokumentacija' ); ?>"><?php echo esc_html( 'https://solo.com.hr/api-dokumentacija' ); ?></a></div>
    <div class="options-wrapper">
      <div class="options-wrapper__tabs">
        <div href="#tab1" class="tab active"><?php esc_html_e( 'Solo API token', 'woo-solo-api' ); ?></div>
        <div href="#tab2" class="tab"><?php esc_html_e( 'Solo API options', 'woo-solo-api' ); ?></div>
        <div href="#tab3" class="tab"><?php esc_html_e( 'WooCommerce additional settings', 'woo-solo-api' ); ?></div>
        <div href="#tab4" class="tab"><?php esc_html_e( 'Mail settings', 'woo-solo-api' ); ?></div>
      </div>
      <div class="options-wrapper__content">
        <div id="tab1" class="tab-content active">
          <div class="option">
            <label for="solo_api_token" class="subtitle"><?php esc_html_e( 'Enter your personal token that you obtained from you SOLO account.', 'woo-solo-api' ); ?></label>
            <input type="text" id="solo_api_token" name="solo_api_token" value="<?php echo esc_attr( get_option( 'solo_api_token' ) ); ?>" />
          </div>
        </div>
        <div id="tab2" class="tab-content">
          <div class="left">
            <div class="option">
              <label for="solo_api_measure" class="subtitle"><?php esc_html_e( 'Unit measure - select the default measure in your shop (e.g. piece, hour, m^3 etc.)', 'woo-solo-api' ); ?></label>
              <select name="solo_api_measure" id="solo_api_measure">
                <option value="1" <?php selected( '1', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( '-', 'woo-solo-api' ); ?></option>
                <option value="2" <?php selected( '2', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'piece', 'woo-solo-api' ); ?></option>
                <option value="3" <?php selected( '3', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'hour', 'woo-solo-api' ); ?></option>
                <option value="4" <?php selected( '4', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'year', 'woo-solo-api' ); ?></option>
                <option value="5" <?php selected( '5', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'km', 'woo-solo-api' ); ?></option>
                <option value="6" <?php selected( '6', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'litre', 'woo-solo-api' ); ?></option>
                <option value="7" <?php selected( '7', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'kg', 'woo-solo-api' ); ?></option>
                <option value="8" <?php selected( '8', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'kWh', 'woo-solo-api' ); ?></option>
                <option value="9" <?php selected( '9', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'm³', 'woo-solo-api' ); ?></option>
                <option value="10" <?php selected( '10', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'tonne', 'woo-solo-api' ); ?></option>
                <option value="11" <?php selected( '11', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'm²', 'woo-solo-api' ); ?></option>
                <option value="12" <?php selected( '12', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'm', 'woo-solo-api' ); ?></option>
                <option value="13" <?php selected( '13', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'day', 'woo-solo-api' ); ?></option>
                <option value="14" <?php selected( '14', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'month', 'woo-solo-api' ); ?></option>
                <option value="15" <?php selected( '15', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'night', 'woo-solo-api' ); ?></option>
                <option value="16" <?php selected( '16', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'card', 'woo-solo-api' ); ?></option>
                <option value="17" <?php selected( '17', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'account', 'woo-solo-api' ); ?></option>
                <option value="18" <?php selected( '18', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'pair', 'woo-solo-api' ); ?></option>
                <option value="19" <?php selected( '19', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'ml', 'woo-solo-api' ); ?></option>
                <option value="20" <?php selected( '20', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'pax', 'woo-solo-api' ); ?></option>
                <option value="21" <?php selected( '21', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'room', 'woo-solo-api' ); ?></option>
                <option value="22" <?php selected( '22', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'appartmant', 'woo-solo-api' ); ?></option>
                <option value="23" <?php selected( '23', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'term', 'woo-solo-api' ); ?></option>
                <option value="24" <?php selected( '24', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'set', 'woo-solo-api' ); ?></option>
                <option value="25" <?php selected( '25', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'package', 'woo-solo-api' ); ?></option>
                <option value="26" <?php selected( '26', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'point', 'woo-solo-api' ); ?></option>
                <option value="27" <?php selected( '27', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'service', 'woo-solo-api' ); ?></option>
                <option value="28" <?php selected( '28', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'pal', 'woo-solo-api' ); ?></option>
                <option value="29" <?php selected( '29', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'kont', 'woo-solo-api' ); ?></option>
                <option value="30" <?php selected( '30', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'čl', 'woo-solo-api' ); ?></option>
                <option value="31" <?php selected( '31', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'tis', 'woo-solo-api' ); ?></option>
                <option value="32" <?php selected( '32', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'sec', 'woo-solo-api' ); ?></option>
                <option value="33" <?php selected( '33', get_option( 'solo_api_measure' ), true ); ?>><?php esc_html_e( 'min', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_payment_type" class="subtitle"><?php esc_html_e( 'Payment options', 'woo-solo-api' ); ?></label>
              <select name="solo_api_payment_type" id="solo_api_payment_type">
                <option value="1" <?php selected( '1', get_option( 'solo_api_payment_type' ), true ); ?>><?php esc_html_e( 'Transactional account', 'woo-solo-api' ); ?></option>
                <option value="2" <?php selected( '2', get_option( 'solo_api_payment_type' ), true ); ?>><?php esc_html_e( 'Cash', 'woo-solo-api' ); ?></option>
                <option value="3" <?php selected( '3', get_option( 'solo_api_payment_type' ), true ); ?>><?php esc_html_e( 'Cards', 'woo-solo-api' ); ?></option>
                <option value="4" <?php selected( '4', get_option( 'solo_api_payment_type' ), true ); ?>><?php esc_html_e( 'Cheque', 'woo-solo-api' ); ?></option>
                <option value="5" <?php selected( '5', get_option( 'solo_api_payment_type' ), true ); ?>><?php esc_html_e( 'Other', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_languages" class="subtitle"><?php esc_html_e( 'Languages', 'woo-solo-api' ); ?></label>
              <select name="solo_api_languages" id="solo_api_languages">
                <option value="1" <?php selected( '1', get_option( 'solo_api_languages' ), true ); ?>><?php esc_html_e( 'Croatian', 'woo-solo-api' ); ?></option>
                <option value="2" <?php selected( '2', get_option( 'solo_api_languages' ), true ); ?>><?php esc_html_e( 'English', 'woo-solo-api' ); ?></option>
                <option value="3" <?php selected( '3', get_option( 'solo_api_languages' ), true ); ?>><?php esc_html_e( 'German', 'woo-solo-api' ); ?></option>
                <option value="4" <?php selected( '4', get_option( 'solo_api_languages' ), true ); ?>><?php esc_html_e( 'French', 'woo-solo-api' ); ?></option>
                <option value="5" <?php selected( '5', get_option( 'solo_api_languages' ), true ); ?>><?php esc_html_e( 'Italian', 'woo-solo-api' ); ?></option>
                <option value="6" <?php selected( '6', get_option( 'solo_api_languages' ), true ); ?>><?php esc_html_e( 'Spanish', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_currency" class="subtitle"><?php esc_html_e( 'Currency', 'woo-solo-api' ); ?></label>
              <select name="solo_api_currency" id="solo_api_currency">
                <option value="1" <?php selected( '1', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'HRK - kn', 'woo-solo-api' ); ?></option>
                <option value="2" <?php selected( '2', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'AUD - $', 'woo-solo-api' ); ?></option>
                <option value="3" <?php selected( '3', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'CAD - $', 'woo-solo-api' ); ?></option>
                <option value="4" <?php selected( '4', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'CZK - Kč', 'woo-solo-api' ); ?></option>
                <option value="5" <?php selected( '5', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'DKK - kr', 'woo-solo-api' ); ?></option>
                <option value="6" <?php selected( '6', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'HUF - Ft', 'woo-solo-api' ); ?></option>
                <option value="7" <?php selected( '7', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'JPY - ¥', 'woo-solo-api' ); ?></option>
                <option value="8" <?php selected( '8', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'NOK - kr', 'woo-solo-api' ); ?></option>
                <option value="9" <?php selected( '9', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'SEK - kr', 'woo-solo-api' ); ?></option>
                <option value="10" <?php selected( '10', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'CHF - CHF', 'woo-solo-api' ); ?></option>
                <option value="11" <?php selected( '11', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'GBP - £', 'woo-solo-api' ); ?></option>
                <option value="12" <?php selected( '12', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'USD - $', 'woo-solo-api' ); ?></option>
                <option value="13" <?php selected( '13', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'BAM - KM', 'woo-solo-api' ); ?></option>
                <option value="14" <?php selected( '14', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'EUR - €', 'woo-solo-api' ); ?></option>
                <option value="15" <?php selected( '15', get_option( 'solo_api_currency' ), true ); ?>><?php esc_html_e( 'PLN - zł', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_bill_offer" class="subtitle"><?php esc_html_e( 'Select if you want to create an invoice or an offer.', 'woo-solo-api' ); ?></label>
              <select name="solo_api_bill_offer" id="solo_api_bill_offer">
                <option value="ponuda" <?php selected( 'ponuda', get_option( 'solo_api_bill_offer' ), true ); ?>><?php esc_html_e( 'Offer', 'woo-solo-api' ); ?></option>
                <option value="racun" <?php selected( 'racun', get_option( 'solo_api_bill_offer' ), true ); ?>><?php esc_html_e( 'Invoice', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_fiscalization" class="subtitle"><?php esc_html_e( 'Check if you want the invoice to be fiscalized. Fiscalization certificate must be added in the SOLO options.', 'woo-solo-api' ); ?></label>
              <input type="checkbox" name="solo_api_fiscalization" id="solo_api_fiscalization" value=1 <?php checked( '1', get_option( 'solo_api_fiscalization' ), true ); ?>>
            </div>
          </div>
          <div class="right">
            <div class="option">
              <label for="solo_api_service_type" class="subtitle"><?php esc_html_e( 'Enter the type of the service - you can find it in your account settings (Usluge -> Tipovi usluga). Has to be unique ID of the service.', 'woo-solo-api' ); ?></label>
              <input type="text" id="solo_api_service_type" name="solo_api_service_type" value="<?php echo esc_attr( get_option( 'solo_api_service_type' ) ); ?>">
            </div>
            <div class="option">
              <label for="solo_api_show_taxes" class="subtitle"><?php esc_html_e( 'Show taxes', 'woo-solo-api' ); ?></label>
              <select name="solo_api_show_taxes" id="solo_api_show_taxes">
                <option value="0" <?php selected( '0', get_option( 'solo_api_show_taxes' ), true ); ?>><?php esc_html_e( 'Don\'t show tax', 'woo-solo-api' ); ?></option>
                <option value="1" <?php selected( '1', get_option( 'solo_api_show_taxes' ), true ); ?>><?php esc_html_e( 'Show tax', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_tax_rate" class="subtitle"><?php esc_html_e( 'Tax rate', 'woo-solo-api' ); ?></label>
              <select name="solo_api_tax_rate" id="solo_api_tax_rate">
                <option value="0" <?php selected( '0', get_option( 'solo_api_tax_rate' ), true ); ?>><?php esc_html_e( '0%', 'woo-solo-api' ); ?></option>
                <option value="5" <?php selected( '5', get_option( 'solo_api_tax_rate' ), true ); ?>><?php esc_html_e( '5%', 'woo-solo-api' ); ?></option>
                <option value="13" <?php selected( '13', get_option( 'solo_api_tax_rate' ), true ); ?>><?php esc_html_e( '13%', 'woo-solo-api' ); ?></option>
                <option value="25" <?php selected( '25', get_option( 'solo_api_tax_rate' ), true ); ?>><?php esc_html_e( '25%', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_recipe_type" class="subtitle"><?php esc_html_e( 'Type of reciepe (only works with reciepe, not with offer)', 'woo-solo-api' ); ?></label>
              <select name="solo_api_recipe_type" id="solo_api_recipe_type">
                <option value="1" <?php selected( '1', get_option( 'solo_api_recipe_type' ), true ); ?>><?php esc_html_e( 'R', 'woo-solo-api' ); ?></option>
                <option value="2" <?php selected( '2', get_option( 'solo_api_recipe_type' ), true ); ?>><?php esc_html_e( 'R1', 'woo-solo-api' ); ?></option>
                <option value="3" <?php selected( '3', get_option( 'solo_api_recipe_type' ), true ); ?>><?php esc_html_e( 'R2', 'woo-solo-api' ); ?></option>
                <option value="4" <?php selected( '4', get_option( 'solo_api_recipe_type' ), true ); ?>><?php esc_html_e( 'No label', 'woo-solo-api' ); ?></option>
                <option value="5" <?php selected( '5', get_option( 'solo_api_recipe_type' ), true ); ?>><?php esc_html_e( 'In advance', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_due_date" class="subtitle"><?php esc_html_e( 'Invoice/Offer due date', 'woo-solo-api' ); ?></label>
              <select name="solo_api_due_date" id="solo_api_due_date">
                <option value="1" <?php selected( '1', get_option( 'solo_api_due_date' ), true ); ?>><?php esc_html_e( '1 week', 'woo-solo-api' ); ?></option>
                <option value="2" <?php selected( '2', get_option( 'solo_api_due_date' ), true ); ?>><?php esc_html_e( '2 weeks', 'woo-solo-api' ); ?></option>
                <option value="3" <?php selected( '3', get_option( 'solo_api_due_date' ), true ); ?>><?php esc_html_e( '3 weeks', 'woo-solo-api' ); ?></option>
              </select>
            </div>
            <div class="option">
              <label for="solo_api_currency_rate" class="subtitle"><?php printf( '%s <a href="https://www.hnb.hr/temeljne-funkcije/monetarna-politika/tecajna-lista/tecajna-lista" target="_blank" rel="noopener noreferrer">%s</a> %s', esc_html__( 'You can check the currency rate at', 'woo-solo-api' ), esc_html__( 'Croatian National Bank', 'woo-solo-api' ), esc_html__( 'to see the current currency rates. If the currency is HRK leave the field blank. Limit the number of decimals to 6 places for other currencies (e.g. 6,123456).', 'woo-solo-api' ) ); ?></label>
              <input type="text" id="solo_api_currency_rate" name="solo_api_currency_rate" value="<?php echo esc_attr( get_option( 'solo_api_currency_rate' ) ); ?>">
            </div>
          </div>
        </div>
        <div id="tab3" class="tab-content">
          <div class="option">
            <label for="solo_api_enable_pin"><input type="checkbox" id="solo_api_enable_pin" name="solo_api_enable_pin" value="1" <?php checked( '1', esc_attr( get_option( 'solo_api_enable_pin' ) ) , true ); ?>><?php esc_html_e( 'Enable the PIN field on the billing and shipping from in the checkout.', 'woo-solo-api' ); ?></label>
          </div>
          <div class="option">
            <label for="solo_api_enable_iban"><input type="checkbox" id="solo_api_enable_iban" name="solo_api_enable_iban" value="1" <?php checked( '1', esc_attr( get_option( 'solo_api_enable_iban' ) ) , true ); ?>><?php esc_html_e( 'Enable the IBAN field on the billing and shipping from in the checkout.', 'woo-solo-api' ); ?></label>
          </div>
          <div class="option">
            <label for="solo_api_send_pdf"><input type="checkbox" id="solo_api_send_pdf" name="solo_api_send_pdf" value="1" <?php checked( '1', esc_attr( get_option( 'solo_api_send_pdf' ) ) , true ); ?>><?php esc_html_e( 'Send the email to the client with the PDF of the order or the recipe.', 'woo-solo-api' ); ?></label>
          </div>
          <div class="option">
            <label for="solo_api_mail_gateway[]" class="subtitle"><?php esc_html_e( 'Create pdf and send mail to the customer only if these payment gateways are selected.', 'woo-solo-api' ); ?></label>
            <?php
            $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
            $checked_gateways = get_option( 'solo_api_mail_gateway' );

            foreach ( $available_gateways as $gateway_key => $gateway_value ) {
            ?>
            <label class="multi_checkbox"><input type="checkbox" name="solo_api_mail_gateway[]" value="<?php echo esc_attr( $gateway_key ); ?>" <?php echo ( is_array( $checked_gateways ) && in_array( esc_attr( $gateway_key ), $checked_gateways, true ) ) ? 'checked' : ''; ?> ><?php echo esc_html( $gateway_value->title ); ?></label><br>
            <?php
            }
            ?>
          </div>
        </div>
        <div id="tab4" class="tab-content">
          <div class="option">
            <label for="solo_api_mail_title" class="subtitle"><?php esc_html_e( 'Set the title of the mail that will be send with the PDF recipe.', 'woo-solo-api' ); ?></label>
            <input type="text" id="solo_api_mail_title" name="solo_api_mail_title" value="<?php echo esc_attr( get_option( 'solo_api_mail_title' ) ); ?>">
          </div>
          <div class="option">
            <label for="solo_api_message" class="subtitle"><?php esc_html_e( 'Type the message that will appear on the mail with the recipe PDF attached.', 'woo-solo-api' ); ?></label>
            <textarea name="solo_api_message" id="solo_api_message" cols="30" rows="10"><?php echo wp_kses_post( get_option( 'solo_api_message' ) ); ?></textarea>
          </div>
          <div class="option">
            <label for="solo_api_change_mail_from" class="subtitle"><?php esc_html_e( 'Change the \'from\' name that shows when WordPress sends the mail.', 'woo-solo-api' ); ?></label>
            <div class="caution"><?php esc_html_e( 'CAUTION: This change is global, every mail send from your WordPress will have this \'from\' name.', 'woo-solo-api' ); ?></div>
            <input type="text" id="solo_api_change_mail_from" name="solo_api_change_mail_from" value="<?php echo esc_attr( get_option( 'solo_api_change_mail_from' ) ); ?>">
          </div>
        </div>
      </div>
    </div>
    <?php submit_button(); ?>
  </form>
</div>
