<?php
/**
 * LSX functions and definitions - Integrations - Extras
 *
 * @package    lsx
 * @subpackage extras
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'lsx_kses_allowed_html' ) ) :

	/**
	 * Enable extra attributes (srcset, sizes) in img tag.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_kses_allowed_html( $allowedtags, $context ) {
		$allowedtags['img']['srcset'] = true;
		$allowedtags['img']['sizes']  = true;
		return $allowedtags;
	}

endif;

add_filter( 'wp_kses_allowed_html', 'lsx_kses_allowed_html', 10, 2 );

if ( ! function_exists( 'lsx_body_class' ) ) :

	/**
	 * Add and remove body_class() classes.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_body_class( $classes ) {
		global $post;

		$header_layout = get_theme_mod( 'lsx_header_layout', 'inline' );
		$classes[]     = 'header-' . $header_layout;

		if ( isset( $post ) ) {
			$classes[] = $post->post_name;
		}

		if ( ! class_exists( 'LSX_Banners' ) ) {
			$post_types = array( 'page', 'post' );
			$post_types = apply_filters( 'lsx_allowed_post_type_banners', $post_types );

			if ( ( is_singular( $post_types ) && has_post_thumbnail() ) || ( is_singular( 'jetpack-portfolio' ) ) ) {
				$classes[] = 'page-has-banner';
			}
		}

		if ( has_nav_menu( 'top-menu' ) || has_nav_menu( 'top-menu-left' ) ) {
			$classes[] = 'has-top-menu';
		}

		$fixed_header = get_theme_mod( 'lsx_header_fixed', false );

		if ( false !== $fixed_header ) {
			$classes[] = 'top-menu-fixed';
		}

		$search_form  = get_theme_mod( 'lsx_header_search', false );

		if ( false !== $search_form ) {
			$classes[] = 'has-header-search';
		}

		$preloader_content  = get_theme_mod( 'lsx_preloader_content_status', false );

		if ( false !== $preloader_content ) {
			$classes[] = 'preloader-content-enable';
		}

		return $classes;
	}

endif;

add_filter( 'body_class', 'lsx_body_class' );

if ( ! function_exists( 'lsx_embed_wrap' ) ) :

	/**
	 * Wrap embedded media as suggested by Readability.
	 *
	 * @package    lsx
	 * @subpackage extras
	 *
	 * @link https://gist.github.com/965956
	 * @link http://www.readability.com/publishers/guidelines#publisher
	 */
	function lsx_embed_wrap( $cache, $url, $attr = '', $post_id = '' ) {
		return '<div class="entry-content-asset">' . $cache . '</div>';
	}

endif;

add_filter( 'embed_oembed_html', 'lsx_embed_wrap', 10, 4 );

if ( ! function_exists( 'lsx_remove_self_closing_tags' ) ) :

	/**
	 * Remove unnecessary self-closing tags.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_remove_self_closing_tags( $input ) {
		return str_replace( ' />', '>', $input );
	}

endif;

add_filter( 'get_avatar',          'lsx_remove_self_closing_tags' ); // <img />
add_filter( 'comment_id_fields',   'lsx_remove_self_closing_tags' ); // <input />
add_filter( 'post_thumbnail_html', 'lsx_remove_self_closing_tags' ); // <img />

if ( ! function_exists( 'lsx_is_element_empty' ) ) :

	/**
	 * Checks if a Nav $element is empty or not.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_is_element_empty( $element ) {
		$element = trim( $element );
		return empty( $element ) ? false : true;
	}

endif;

if ( ! function_exists( 'lsx_get_thumbnail' ) ) :

	/**
	 * return the responsive images.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_get_thumbnail( $size, $image_src = false ) {
		if ( false === $image_src ) {
			$post_id           = get_the_ID();
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		} elseif ( false !== $image_src ) {
			if ( is_numeric( $image_src ) ) {
				$post_thumbnail_id = $image_src;
			} else {
				$post_thumbnail_id = lsx_get_attachment_id_from_src( $image_src );
			}
		}

		$size = apply_filters( 'lsx_thumbnail_size', $size );
		$img  = false;

		if ( 'lsx-thumbnail-wide' === $size || 'thumbnail' === $size ) {
			$srcset = false;
			$img    = wp_get_attachment_image_src( $post_thumbnail_id, $size );
			$img    = $img[0];
		} else {
			$srcset = true;
			$img = wp_get_attachment_image_srcset( $post_thumbnail_id, $size );

			if ( false === $img ) {
				$srcset = false;
				$img = wp_get_attachment_image_src( $post_thumbnail_id, $size );
				$img = $img[0];
			}
		}

		if ( $srcset ) {
			$img = '<img alt="' . the_title_attribute( 'echo=0' ) . '" class="attachment-responsive wp-post-image lsx-responsive" srcset="' . esc_attr( $img ) . '" />';
		} else {
			$img = '<img alt="' . the_title_attribute( 'echo=0' ) . '" class="attachment-responsive wp-post-image lsx-responsive" src="' . esc_url( $img ) . '" />';
		}

		$img = apply_filters( 'lsx_lazyload_filter_images', $img );
		return $img;
	}

endif;

if ( ! function_exists( 'lsx_thumbnail' ) ) :

	/**
	 * Output the Resonsive Images.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_thumbnail( $size = 'thumbnail', $image_src = false ) {
		echo wp_kses_post( lsx_get_thumbnail( $size, $image_src ) );
	}

endif;

if ( ! function_exists( 'lsx_get_attachment_id_from_src' ) ) :

	/**
	 * Gets the attachments ID from the src.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_get_attachment_id_from_src( $image_src ) {
		$post_id = wp_cache_get( $image_src, 'lsx_get_attachment_id_from_src' );

		if ( false === $post_id ) {
			global $wpdb;
			$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid='%s' LIMIT 1", $image_src ) );
			wp_cache_set( $image_src, $post_id, 'lsx_get_attachment_id_from_src', 3600 );
		}

		return $post_id;
	}

endif;

if ( ! function_exists( 'lsx_page_banner' ) ) :

	/**
	 * Add Featured Image as Banner on Single Pages.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_page_banner() {
		$post_types = array( 'page', 'post' );
		$post_types = apply_filters( 'lsx_allowed_post_type_banners', $post_types );

		if ( ( is_singular( $post_types ) && has_post_thumbnail() ) || ( is_singular( 'jetpack-portfolio' ) ) ) :
			$bg_image = '';

			if ( has_post_thumbnail() ) {
				$bg_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
				$bg_image = $bg_image[0];
			}

			if ( ! empty( $bg_image ) ) :
				?>
					<div class="page-banner-wrap">
						<div class="page-banner">
							<div class="page-banner-image" style="background-image:url(<?php echo esc_attr( $bg_image ); ?>);"></div>

							<div class="container">
								<header class="page-header">
									<h1 class="page-title"><?php the_title(); ?></h1>
									<?php lsx_banner_content(); ?>
								</header>
							</div>

							<?php lsx_banner_inner_bottom(); ?>
						</div>
					</div>
				<?php
			endif;
		endif;
	}

endif;

add_action( 'lsx_header_after', 'lsx_page_banner' );

if ( ! function_exists( 'lsx_browser_body_class' ) ) :

	/**
	 * Adding browser and user-agent classes to body.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_browser_body_class( $classes ) {
		$http_user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		$http_user_agent = ! empty( $http_user_agent ) ? $http_user_agent : '';

		global $is_lynx, $is_gecko, $is_ie, $is_opera, $is_ns4, $is_safari, $is_chrome, $is_iphone;

		if ( $is_lynx ) {
			$classes[] = 'lynx';
		} elseif ( $is_gecko ) {
			$classes[] = 'gecko';
		} elseif ( $is_opera ) {
			$classes[] = 'opera';
		} elseif ( $is_ns4 ) {
			$classes[] = 'ns4';
		} elseif ( $is_safari ) {
			$classes[] = 'safari';
		} elseif ( $is_chrome ) {
			$classes[] = 'chrome';
		} elseif ( $is_ie ) {
			$classes[] = 'ie';

			if ( preg_match( '/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $http_user_agent, $browser_version ) ) {
				$classes[] = 'ie' . $browser_version[1];
			}
		} else {
			$classes[] = 'unknown';
		}

		if ( $is_iphone ) {
			$classes[] = 'iphone';
		}

		if ( stristr( $http_user_agent, 'mac' ) ) {
			$classes[] = 'osx';
		} elseif ( stristr( $http_user_agent, 'linux' ) ) {
			$classes[] = 'linux';
		} elseif ( stristr( $http_user_agent, 'windows' ) ) {
			$classes[] = 'windows';
		}

		return $classes;
	}

endif;

add_filter( 'body_class', 'lsx_browser_body_class' );

if ( ! function_exists( 'lsx_form_submit_button' ) ) :

	/**
	 * filter the Gravity Forms button type.
	 *
	 * @package    lsx
	 * @subpackage extras
	 *
	 * @param		$button		String
	 * @param		$form		Object
	 * @return		String
	 */
	function lsx_form_submit_button( $button, $form ) {
		return "<button class='btn btn-primary' id='gform_submit_button_{$form["id"]}'><span>Submit</span></button>";
	}

endif;

add_filter( 'gform_submit_button', 'lsx_form_submit_button', 10, 2 );

if ( ! function_exists( 'lsx_excerpt_more' ) ) :

	/**
	 * Replaces the excerpt "more" text by a link.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_excerpt_more( $more ) {
		return '...';
	}

endif;

add_filter( 'excerpt_more', 'lsx_excerpt_more' );

if ( ! function_exists( 'lsx_the_excerpt_filter' ) ) :

	/**
	 * Add a continue reading link to the excerpt.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_the_excerpt_filter( $excerpt ) {
		$show_full_content = has_post_format( apply_filters( 'lsx_the_excerpt_filter_post_types', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ) ) );

		if ( ! $show_full_content ) {
			if ( '' !== $excerpt  && ! stristr( $excerpt, 'moretag' ) ) {
				$pagination = wp_link_pages( array(
					'before'      => '<div class="lsx-postnav-wrapper"><div class="lsx-postnav">',
					'after'       => '</div></div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'echo'        => 0,
				) );

				if ( ! empty( $pagination ) ) {
					$excerpt .= $pagination;
				} else {
					$excerpt .= '<p><a class="moretag" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Continue reading', 'lsx' ) . '</a></p>';
				}
			}
		}

		return $excerpt;
	}

endif;

add_filter( 'the_excerpt', 'lsx_the_excerpt_filter' , 1 , 20 );

if ( ! function_exists( 'lsx_custom_wp_trim_excerpt' ) ) :

	/**
	 * Allow HTML tags in excerpt.
	 *
	 * @package    lsx
	 * @subpackage extras
	 */
	function lsx_custom_wp_trim_excerpt( $wpse_excerpt ) {
		global $post;
		$raw_excerpt = $wpse_excerpt;

		if ( empty( $wpse_excerpt ) ) {
			$wpse_excerpt      = get_the_content( '' );
			$show_full_content = has_post_format( array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ) );

			if ( ! $show_full_content ) {
				$wpse_excerpt = strip_shortcodes( $wpse_excerpt );
				$wpse_excerpt = apply_filters( 'the_content', $wpse_excerpt );
				$wpse_excerpt = str_replace( ']]>', ']]>', $wpse_excerpt );
				$wpse_excerpt = strip_tags( $wpse_excerpt, '<h1>,<h2>,<h3>,<h4>,<h5>,<h6>,<a>,<button>,<blockquote>,<p>,<br>,<b>,<strong>,<i>,<u>,<ul>,<ol>,<li>,<span>,<div>' );

				$excerpt_word_count = 50;
				$excerpt_word_count = apply_filters( 'excerpt_length', $excerpt_word_count );

				$tokens         = array();
				$excerpt_output = '';
				$has_more       = false;
				$count          = 0;

				preg_match_all( '/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens );

				foreach ( $tokens[0] as $token ) {
					if ( $count >= $excerpt_word_count ) {
						$excerpt_output .= trim( $token );
						$has_more = true;
						break;
					}

					$count++;
					$excerpt_output .= $token;
				}

				$wpse_excerpt = trim( force_balance_tags( $excerpt_output ) );

				if ( $has_more ) {
					$excerpt_end = '<a class="moretag" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'More', 'lsx' ) . '</a>';
					$excerpt_end = apply_filters( 'excerpt_more', ' ' . $excerpt_end );

					$pos = strrpos( $wpse_excerpt, '</' );

					if ( false !== $pos ) {
						// Inside last HTML tag
						$wpse_excerpt = substr_replace( $wpse_excerpt, $excerpt_end, $pos, 0 ); /* Add read more next to last word */
					} else {
						// After the content
						$wpse_excerpt .= $excerpt_end; /*Add read more in new paragraph */
					}
				}
			} else {
				$wpse_excerpt = apply_filters( 'the_content', $wpse_excerpt );
				$wpse_excerpt = str_replace( ']]>', ']]>', $wpse_excerpt );
				//$wpse_excerpt = strip_tags( $wpse_excerpt, '<blockquote>,<p>' );
				$wpse_excerpt = trim( force_balance_tags( $wpse_excerpt ) );
			}

			return $wpse_excerpt;
		}

		return apply_filters( 'lsx_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt );
	}

endif;

remove_filter( 'get_the_excerpt', 'wp_trim_excerpt' );
add_filter( 'get_the_excerpt', 'lsx_custom_wp_trim_excerpt' );
