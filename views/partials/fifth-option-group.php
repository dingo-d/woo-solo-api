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
<input class="options-wrapper__input" id="apiTest" type="radio" name="tabs" />
<label class="options-wrapper__label" for="apiTest"><?php _e('Solo API test', 'woo-solo-api'); ?></label>
<div class="options-wrapper__content">
	<div class="content">
		<div class="content__item content__item--one-column">
			<div class="content__item-label">
				<h3 class="content__item-label--heading">
					<?php esc_html_e( 'This serves for testing purposes only', 'woo-solo-api' ); ?>
				</h3>
				<p class="content__item-label--notice">
					<?php esc_html_e( 'Pressing the button will make a request to your Solo API account, and will list all the invoices you have' ); ?></p>
				</p>
			</div>
		</div>
		<div class="content__item">
			<div class="content__item-label">
				<button class="button button-primary js-solo-api-send"><?php esc_html_e( 'Make a request', 'woo-solo-api' ); ?></button>
			</div>
			<div class="content__item-content">
				<pre class="js-solo-api-request" readonly></pre>
			</div>
		</div>
	</div>
</div>

