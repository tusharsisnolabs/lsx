<?php
/**
 * LSX functions and definitions - WooCommerce.
 *
 * @package    lsx
 * @subpackage layout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'lsx_wc_support' ) ) :

	/**
	 * WooCommerce support.
	 *
	 * @package    lsx
	 * @subpackage woocommerce
	 */
	function lsx_wc_support() {
		add_theme_support( 'woocommerce' );
	}

endif;

add_action( 'after_setup_theme', 'lsx_wc_support' );

if ( ! function_exists( 'lsx_wc_scripts_add_styles' ) ) :

	/**
	 * WooCommerce enqueue styles.
	 *
	 * @package    lsx
	 * @subpackage woocommerce
	 */
	function lsx_wc_scripts_add_styles() {
		wp_enqueue_style( 'woocommerce-lsx', get_template_directory_uri() . '/assets/css/woocommerce.css', array( 'lsx_main' ), LSX_VERSION );
		wp_style_add_data( 'woocommerce-lsx', 'rtl', 'replace' );

		// Remove select2 added by WooCommerce

		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'select2' );

		wp_dequeue_script( 'select2' );
		wp_deregister_script( 'select2' );
	}

endif;

add_action( 'wp_enqueue_scripts', 'lsx_wc_scripts_add_styles' );

if ( ! function_exists( 'lsx_wc_form_field_args' ) ) :

	/**
	 * WooCommerce form fields.
	 *
	 * @package    lsx
	 * @subpackage woocommerce
	 */
	function lsx_wc_form_field_args( $args, $key, $value ) {
		$args['input_class'][] = 'form-control';

		return $args;
	}

endif;

add_action( 'woocommerce_form_field_args', 'lsx_wc_form_field_args', 10, 3 );

if ( ! function_exists( 'lsx_wc_theme_wrapper_start' ) ) :

	/**
	 * WooCommerce wrapper start.
	 *
	 * @package    lsx
	 * @subpackage woocommerce
	 */
	function lsx_wc_theme_wrapper_start() {
		echo '<div id="primary" class="content-area col-sm-12">';
		echo '<main id="main" class="site-main" role="main">';
	}

endif;

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
add_action( 'woocommerce_before_main_content', 'lsx_wc_theme_wrapper_start' );


if ( ! function_exists( 'lsx_wc_theme_wrapper_end' ) ) :

	/**
	 * WooCommerce wrapper end.
	 *
	 * @package    lsx
	 * @subpackage woocommerce
	 */
	function lsx_wc_theme_wrapper_end() {
		echo '</div>';
		echo '</div>';
	}

endif;

remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_after_main_content', 'lsx_wc_theme_wrapper_end' );

if ( ! function_exists( 'lsx_wc_disable_banner' ) ) :

	/**
	 * Disable LSX Banners banner in some WC pages.
	 *
	 * @package    lsx
	 * @subpackage woocommerce
	 */
	function lsx_wc_disable_lsx_banner( $disabled ) {
		if ( is_cart() || is_checkout() || is_account_page() ) {
			$disabled = true;
		}

		return $disabled;
	}

endif;

add_filter( 'lsx_banner_disable', 'lsx_wc_disable_lsx_banner' );
