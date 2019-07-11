<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WSSV_Settings.
 *
 * @class    Iconic_WSSV_Settings
 * @version  1.0.0
 * @author   Iconic
 */
class Iconic_WSSV_Settings {
	/**
	 * Run.
	 */
	public static function init() {
		add_action( 'wpsf_after_settings_iconic_wssv', array( __CLASS__, 'process_modal' ), 10 );
		add_filter( 'wpsf_show_save_changes_button_iconic_wssv', '__return_false' );
		add_filter( 'wpsf_show_tab_links_iconic_wssv', '__return_false' );
	}

	/**
	 * Output process modal.
	 */
	public static function process_modal() {
		?>
		<div class="process-overlay"></div>
		<div class="process process--variation-visibility">
			<div class="process__content process__content--loading">
				<h3><?php _e( 'Loading...', 'iconic-woo-show-single-variations' ); ?></h3>
			</div>
			<div class="process__content process__content--processing">
				<h3><?php _e( 'Processing', 'iconic-woo-show-single-variations' ); ?>
					<span class="process__count-from"></span> <?php _e( 'to', 'iconic-woo-show-single-variations' ); ?>
					<span class="process__count-to"></span> <?php _e( 'of', 'iconic-woo-show-single-variations' ); ?>
					<span class="process__count-total"></span> <?php _e( 'items', 'iconic-woo-show-single-variations' ); ?>, <?php _e( 'please wait...', 'iconic-woo-show-single-variations' ); ?>
				</h3>
				<div class="process__loading-bar">
					<div class="process__loading-bar-fill"></div>
				</div>
			</div>
			<div class="process__content process__content--complete">
				<h3><?php _e( 'Process complete', 'iconic-woo-show-single-variations' ); ?></h3>
				<p>
					<span class="process__count-total"></span> <?php _e( 'items were processed.', 'iconic-woo-show-single-variations' ); ?>
				</p>
				<a href="javascript: void(0);" class="button button-secondary process__close"><?php _e( 'Close', 'iconic-woo-show-single-variations' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Process product visibility link
	 *
	 * @return string
	 */
	public static function get_process_product_visibility_link() {
		ob_start();

		?>
		<a href="javascript: void(0);" class="button button-secondary" data-iconic-wssv-ajax="process_product_visibility"><?php _e( 'Process Product Visibility', 'iconic-woo-show-single-variations' ); ?></a>
		<?php

		return ob_get_clean();
	}
}