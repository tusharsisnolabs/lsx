<?php
/**
 * LSX functions and definitions - Bootstrap Navigation Walker
 *
 * @package    lsx
 * @subpackage navigation
 * @category   bootstrap-navigation-walker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Walker_Nav_Menu' ) ) {
	return;
}

if ( ! class_exists( 'LSX_Bootstrap_Navwalker' ) ) :

	/**
	 * Cleaner Bootstrap walker
	 *
	 * @package    lsx
	 * @subpackage navigation
	 * @category   bootstrap-navigation-walker
	 */
	class LSX_Bootstrap_Navwalker extends Walker_Nav_Menu {

		/**
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
		}

		/**
		 * @param string $item Passed by reference. Used to append additional content.
		 */
		public function filter_default_pages( &$item ) {
			return $item;
		}

		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param int $current_page Menu item ID.
		 * @param object $args
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			/**
			 * If this is a default menu being called we need to fix
			 * the item object thats coming through.
			 */
			if ( ! isset( $item->title ) ) {
				return;
			}

			/**
			 * Dividers, Headers or Disabled
			 * =============================
			 * Determine whether the item is a Divider, Header, Disabled or regular
			 * menu item. To prevent errors we use the strcasecmp() function to so a
			 * comparison that is not case sensitive. The strcasecmp() function returns
			 * a 0 if the strings are equal.
			 */
			if ( 0 == strcasecmp( $item->attr_title, 'divider' ) && 1 === $depth ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} elseif ( 0 == strcasecmp( $item->title, 'divider' ) && 1 === $depth ) {
				$output .= $indent . '<li role="presentation" class="divider">';
			} elseif ( 0 == strcasecmp( $item->attr_title, 'dropdown-header' ) && 1 === $depth ) {
				$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
			} elseif ( 0 == strcasecmp( $item->attr_title, 'disabled' ) ) {
				$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
			} else {
				$class_names = '';
				$value       = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;

				$classes = apply_filters( 'lsx_nav_menu_css_class', array_filter( $classes ), $item, $args , $depth );

				$class_names = join( ' ', $classes );

				if ( $args->has_children )
					$class_names .= ' dropdown';

				if ( in_array( 'current-menu-item', $classes ) )
					$class_names .= ' active';

				if ( in_array( 'current-menu-parent', $classes ) )
					$class_names .= ' active';

				//Check if this is ment to be a "social" type menu
				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li' . $id . $value . $class_names . '>';

				$atts = array();
				$atts['title']  = ! empty( $item->title ) ? $item->title : '';
				$atts['target'] = ! empty( $item->target ) ? $item->target : '';
				$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

				// If item has_children add atts to a.
				if ( $args->has_children ) {
					$atts['href']          = ! empty( $item->url ) ? $item->url : '';
					$atts['data-toggle']   = 'dropdown';
					$atts['class']         = 'dropdown-toggle';
					$atts['aria-haspopup'] = 'true';
				} else {
					$atts['href'] = ! empty( $item->url ) ? $item->url : '';
				}

				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$item_output = $args->before;

				$item_output .= '<a' . $attributes . '>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
		}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @param object $element Data object
		 * @param array $children_elements List of elements to continue traversing.
		 * @param int $max_depth Max depth to traverse.
		 * @param int $depth Depth of current element.
		 * @param array $args
		 * @param string $output Passed by reference. Used to append additional content.
		 * @return null Null on failure with no changes to parameters.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}

			$id_field = $this->db_fields['id'];

			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
			}

			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a manu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 *
		 */
		public static function fallback( $args ) {
			if ( current_user_can( 'manage_options' ) ) {
				$fb_output = null;

				if ( $args['container'] ) {
					$fb_output = '<' . $args['container'];

					if ( $args['container_id'] ) {
						$fb_output .= ' id="' . $args['container_id'] . '"';
					}

					if ( $args['container_class'] ) {
						$fb_output .= ' class="' . $args['container_class'] . '"';
					}

					$fb_output .= '>';
				}

				$fb_output .= '<ul';

				if ( $args['menu_id'] ) {
					$fb_output .= ' id="' . $args['menu_id'] . '"';
				}

				if ( $args['menu_class'] ) {
					$fb_output .= ' class="' . $args['menu_class'] . '"';
				}

				$fb_output .= '>';
				$fb_output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'lsx' ) . '</a></li>';
				$fb_output .= '</ul>';

				if ( $args['container'] ) {
					$fb_output .= '</' . $args['container'] . '>';
				}

				echo wp_kses_post( $fb_output );
			}
		}

	}

endif;
