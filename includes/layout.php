<?php
/**
 * LSX functions and definitions - Layout.
 *
 * @package    lsx
 * @subpackage layout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'lsx_layout_selector' ) ) :

	/**
	 * Layout selector.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_layout_selector( $class, $area = 'site' ) {
		$layout       = get_theme_mod( 'lsx_layout', '2cr' );
		$layout       = apply_filters( 'lsx_layout', $layout );
		$default_size = 'sm';
		$size         = apply_filters( 'lsx_bootstrap_column_size', $default_size );

		switch ( $layout ) {
			case '1c':
				$main_class    = 'col-' . $size . '-12';
				$sidebar_class = 'col-' . $size . '-12';
				break;
			case '2cr':
				$main_class    = 'col-' . $size . '-9';
				$sidebar_class = 'col-' . $size . '-3';
				break;
			case '2cl':
				$main_class    = 'col-' . $size . '-9 col-' . $size . '-push-3';
				$sidebar_class = 'col-' . $size . '-3 col-' . $size . '-pull-9';
				break;
			default:
				$main_class    = 'col-' . $size . '-9';
				$sidebar_class = 'col-' . $size . '-3';
				break;
		}

		if ( 'main' === $class ) {
			return $main_class;
		}

		if ( 'sidebar' === $class ) {
			return $sidebar_class;
		}
	}

endif;

if ( ! function_exists( 'lsx_main_class' ) ) :

	/**
	 * .main classes.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_main_class() {
		return lsx_layout_selector( 'main' );
	}

endif;

if ( ! function_exists( 'lsx_sidebar_class' ) ) :

	/**
	 * .sidebar classes.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_sidebar_class() {
		return lsx_layout_selector( 'sidebar' );
	}

endif;

if ( ! function_exists( 'lsx_header_classes' ) ) :

	/**
	 * Output the classes for the header.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_header_classes( $additional = false ) {
		$classes = 'banner navbar navbar-default';

		if ( false !== $additional ) {
			$classes .= ' ' . $additional;
		}

		echo esc_attr( $classes );
	}

endif;

if ( ! function_exists( 'lsx_top_menu_classes' ) ) :

	/**
	 * Output the classes for the top-menu.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_top_menu_classes( $additional = false ) {
		$classes = 'top-menu-default';

		if ( false !== $additional ) {
			$classes .= ' ' . $additional;
		}

		echo esc_attr( $classes );
	}

endif;

if ( ! function_exists( 'lsx_add_footer_sidebar_area' ) ) :

	/**
	 * Output the Footer CTA and/pr Footer Widgets.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_add_footer_sidebar_area() {
		if ( is_active_sidebar( 'sidebar-footer-cta' ) ) : ?>
			<div id="footer-cta">
				<div class="container">
					<div class="lsx-full-width">
						<div class="lsx-hero-unit">
							<?php dynamic_sidebar( 'sidebar-footer-cta' ); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
			<div id="footer-widgets">
				<div class="container">
					<div class="row">
						<?php dynamic_sidebar( 'sidebar-footer' ); ?>
					</div>
				</div>
			</div>
		<?php endif;
	}

endif;

add_action( 'lsx_footer_before', 'lsx_add_footer_sidebar_area' );

if ( ! function_exists( 'lsx_global_header' ) ) :

	/**
	 * Displays the global header.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_global_header() {
		$show_on_front = get_option( 'show_on_front' );
		$default_size  = 'sm';
		$size          = apply_filters( 'lsx_bootstrap_column_size', $default_size );

		if ( is_singular( 'post' ) ) :
			$format = get_post_format();

			if ( false === $format ) {
				$format = 'standard';
			}

			$format = lsx_translate_format_to_fontawesome( $format );
			?>
			<div class="archive-header-wrapper col-<?php echo esc_attr( $size ); ?>-12">
				<header class="archive-header">
					<h1 class="archive-title">
						<i class="format-link fa fa-<?php echo esc_attr( $format ); ?>"></i>
						<span><?php the_title(); ?></span>
					</h1>
				</header>
			</div>
			<?php
		elseif ( ( is_page() || is_single() ) && ( 'page' !== $show_on_front || ! is_front_page() ) ) :
			?>
			<div class="archive-header-wrapper col-<?php echo esc_attr( $size ); ?>-12">
				<header class="archive-header">
					<h1 class="archive-title"><?php the_title(); ?></h1>
				</header>
			</div>
			<?php
		elseif ( is_search() ) :
			?>
			<div class="archive-header-wrapper col-<?php echo esc_attr( $size ); ?>-12">
				<header class="archive-header">
					<h1 class="archive-title">
						<?php
							printf(
								/* Translators: %s: search term/query */
								esc_html__( 'Search Results for: %s', 'lsx' ),
								'<span>' . get_search_query() . '</span>'
							);
						?>
					</h1>
				</header>
			</div>
			<?php
		elseif ( is_author() ) :
			?>
			<div class="archive-header-wrapper col-<?php echo esc_attr( $size ); ?>-12">
				<header class="archive-header">
					<h1 class="archive-title"><?php the_archive_title(); ?></h1>
					<?php the_archive_description(); ?>
				</header>
			</div>
			<?php
		elseif ( is_archive() && class_exists( 'WooCommerce' ) && is_post_type_archive( 'product' ) ) :
			?>
			<div class="archive-header-wrapper col-<?php echo esc_attr( $size ); ?>-12">
				<header class="archive-header">
					<h1 class="archive-title"><?php the_archive_title(); ?></h1>
					<?php the_archive_description(); ?>
				</header>
			</div>
			<?php
		elseif ( is_archive() ) :
			?>
			<div class="archive-header-wrapper col-<?php echo esc_attr( $size ); ?>-12">
				<header class="archive-header">
					<h1 class="archive-title">
						<?php if ( has_post_format() && ! is_category() && ! is_tag() && ! is_date() && ! is_tax( 'post_format' ) ) { ?>
							<?php the_archive_title( esc_html__( 'Type:', 'lsx' ) ); ?>
						<?php } else { ?>
							<?php the_archive_title(); ?>
						<?php } ?>
					</h1>

					<?php the_archive_description(); ?>
				</header>
			</div>
			<?php
		endif;
	}

endif;

add_action( 'lsx_content_wrap_before', 'lsx_global_header' );

if ( ! function_exists( 'lsx_blog_header' ) ) :

	/**
	 * Displays the global header in default Blog landing.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_blog_header() {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			$queried_object = get_queried_object();

			if ( (int) get_option( 'page_for_posts' ) === $queried_object->ID ) :
				?>
				<header class="archive-header">
					<h1 class="archive-title"><?php esc_html_e( 'Blog', 'lsx' ); ?></h1>
				</header>
				<?php
			endif;
		}
	}

endif;

add_action( 'lsx_content_wrap_before', 'lsx_blog_header' );

if ( ! function_exists( 'lsx_add_viewport_meta_tag' ) ) :

	/**
	 * Add Viewport Meta Tag to head.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_add_viewport_meta_tag() {
		?>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
		<?php
	}

endif;

add_action( 'wp_head', 'lsx_add_viewport_meta_tag' );

if ( ! function_exists( 'lsx_header_search_form' ) ) :

	/**
	 * Add a search form to just above the nav menu.
	 *
	 * @package    lsx
	 * @subpackage layout
	 */
	function lsx_header_search_form() {
		$search_form = get_theme_mod( 'lsx_header_search', false );

		if ( true === $search_form || is_customize_preview() ) {
			get_search_form( true );
		}
	}

endif;

add_action( 'lsx_nav_before', 'lsx_header_search_form', 0 );
