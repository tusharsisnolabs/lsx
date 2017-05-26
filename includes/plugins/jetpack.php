<?php
/**
 * LSX functions and definitions - Integrations - Jetpack.
 *
 * @package    lsx
 * @subpackage plugins
 * @category   jetpack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'lsx_single_remove_share' ) ) :

	/**
	 * Remove the sharing from single.
	 *
	 * @package    lsx
	 * @subpackage plugins
	 * @category   jetpack
	 */
	function lsx_single_remove_share() {
		if ( is_single() ) {
			remove_filter( 'the_content', 'sharing_display', 19 );
			remove_filter( 'the_excerpt', 'sharing_display', 19 );

			if ( class_exists( 'Jetpack_Likes' ) ) {
				remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
			}
		}
	}

endif;

add_action( 'loop_start', 'lsx_single_remove_share' );

if ( ! function_exists( 'lsx_jetpack_infinite_scroll_after_setup' ) ) :

	/**
	 * Adds the theme_support for Jetpacks Infinite Scroll.
	 *
	 * @package    lsx
	 * @subpackage plugins
	 * @category   jetpack
	 */
	function lsx_jetpack_infinite_scroll_after_setup() {
		$infinite_scroll_args = array(
			'container'      => 'main',
			'type'           => 'click',
			'posts_per_page' => get_option( 'posts_per_page', 10 ),
			'render'         => 'lsx_infinite_scroll_render',
		);

		add_theme_support( 'infinite-scroll', $infinite_scroll_args );
	}

endif;

add_action( 'after_setup_theme', 'lsx_jetpack_infinite_scroll_after_setup' );

if ( ! function_exists( 'lsx_infinite_scroll_render' ) ) :

	/**
	 * Set the code to be rendered on for calling posts,
	 * hooked to template parts when possible.
	 *
	 * @package    lsx
	 * @subpackage plugins
	 * @category   jetpack
	 */
	function lsx_infinite_scroll_render() {
		global $wp_query;

		while ( have_posts() ) {
			the_post();
			get_template_part( 'partials/content', get_post_type() );
		}
	}

endif;
