<?php

/**
 * Options page wrapper
 *
 * Used for displaying all the Woo Solo Api plugin settings
 *
 * @since 2.0.0
 * @package MadeByDenis\WooSoloApi\Views
 */

namespace MadeByDenis\WooSoloApi\Views;

?>
<div class="wrap">
	<h2><?php echo esc_html($this->pageTitle); ?></h2>
	<div class="notice plugin-notice">
		<?php esc_html_e( 'For more details on the options you can read the official SOLO API documentation here: ', 'woo-solo-api' ); ?>
		<a href="https://solo.com.hr/api-dokumentacija">https://solo.com.hr/api-dokumentacija</a>
	</div>
	<div class="options-wrapper">
		<div class="options-wrapper__tab">
			<?php echo $this->renderPartial( 'views/partials/first-option-group' ); // phpcs:ignore ?>
		</div>
		<div class="options-wrapper__tab">
			<?php echo $this->renderPartial( 'views/partials/second-option-group' ); // phpcs:ignore ?>
		</div>
		<div class="options-wrapper__tab">
			<?php echo $this->renderPartial( 'views/partials/third-option-group' ); // phpcs:ignore ?>
		</div>
		<div class="options-wrapper__tab">
			<?php echo $this->renderPartial( 'views/partials/fourth-option-group' ); // phpcs:ignore ?>
		</div>
		<div class="options-wrapper__tab">
			<?php echo $this->renderPartial( 'views/partials/fifth-option-group' ); // phpcs:ignore ?>
		</div>
		<button class="button button-primary js-save-options">
			<?php esc_html_e('Save settings', 'woo-solo-api'); ?>
		</button>
	</div>
</div>
