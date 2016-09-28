<?php
/**
 * WooCommerce Layout and  Functionality
 *
 * @package lsx
 * @subpackage woocommerce
 */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'lsx_woocommerce_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'lsx_woocommerce_after_content', 10 );

/**
 * Adds the top and primary divs for the layout.
 *
 * @package lsx
 * @subpackage woocommerce
 * @category 	layout
 */
function lsx_woocommerce_before_content() {
	?>
	<?php lsx_content_wrap_before(); ?>

	<div id="primary" class="content-area <?php echo esc_attr( lsx_main_class() ); ?>">

		<?php lsx_content_before(); ?>

		<main id="main" class="site-main">
		
		<?php lsx_content_top(); ?>
	<?php
}

/**
 * Adds the closing divs for primary and main to woocommerce.
 *
 * @package lsx
 * @subpackage woocommerce
 * @category 	layout
 */
function lsx_woocommerce_after_content() {
	?>
		<?php lsx_content_bottom(); ?>

		</main><!-- #main -->

		<?php lsx_content_after(); ?>
		
	</div><!-- #primary -->

	<?php lsx_content_wrap_after(); ?>
	<?php
}

/**
 * Removes WooCommerce plugin styles and enqueues WooCommerce styles from the theme instead.
 *
 * @package lsx
 * @subpackage woocommerce
 * @category 	styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

function lsx_woocommerce_styles() {
	wp_enqueue_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
}
add_action( 'wp_enqueue_scripts', 'lsx_woocommerce_styles' );
