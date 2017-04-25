<?php
/**
 * LSX functions and definitions - Customizer.
 *
 * @package    lsx
 * @subpackage customizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'LSX_Theme_Customizer' ) ) :

	/**
	 * Customizer Configuration File
	 *
	 * @package    lsx
	 * @subpackage customizer
	 */
	class LSX_Theme_Customizer {

		public $post_types = array();
		private $controls  = array();

		/**
		 * Initialize the plugin by setting localization and loading public scripts and styles.
		 */
		public function __construct( $controls ) {
			require get_template_directory() . '/includes/classes/class-lsx-google-font.php';
			require get_template_directory() . '/includes/classes/class-lsx-google-font-collection.php';
			require get_template_directory() . '/includes/classes/class-lsx-customize-core-control.php';
			require get_template_directory() . '/includes/classes/class-lsx-customize-layout-control.php';
			require get_template_directory() . '/includes/classes/class-lsx-customize-font-control.php';
			require get_template_directory() . '/includes/classes/class-lsx-customize-header-layout-control.php';

			$this->controls = $controls;

			add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ), 20 );
			add_action( 'customize_register',     array( $this, 'customizer' ), 11 );

			add_action( 'wp_ajax_customizer_site_title',        array( $this, 'ajax_site_title' ) );
			add_action( 'wp_ajax_nopriv_customizer_site_title', array( $this, 'ajax_site_title' ) );
		}

		/**
		 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
		 */
		public function customize_preview_js() {
			wp_enqueue_script( 'lsx_customizer', get_template_directory_uri() . '/assets/js/admin/customizer.js', array( 'customize-preview' ), LSX_VERSION, true );

			wp_localize_script( 'lsx_customizer', 'lsx_customizer_params', array(
				'template_directory' => get_template_directory_uri(),
			) );
		}

		/**
		 * Create customiser controls.
		 */
		public function customizer( $wp_customize ) {
			// Start panels
			if ( ! empty( $this->controls['panels'] ) ) {
				foreach ( $this->controls['panels'] as $panel_slug => $args ) {
					$this->add_panel( $panel_slug, $args, $wp_customize );
				}
			}

			// Start sections
			if ( ! empty( $this->controls['sections'] ) ) {
				foreach ( $this->controls['sections'] as $section_slug => $args ) {
					$this->add_section( $section_slug, $args, $wp_customize );
				}
			}

			// Start settings
			if ( ! empty( $this->controls['settings'] ) ) {
				foreach ( $this->controls['settings'] as $settings_slug => $args ) {
					$this->add_setting( $settings_slug, $args, $wp_customize );
				}
			}

			// Start fields
			if ( ! empty( $this->controls['fields'] ) ) {
				foreach ( $this->controls['fields'] as $field_slug => $args ) {
					$this->add_control( $field_slug, $args, $wp_customize );
				}
			}

			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
			$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
		}

		/**
		 * Create a panel.
		 */
		private function add_panel( $slug, $args, $wp_customize ) {
			$default_args = array(
				'title'       => null,
				'description' => null,
			);

			$wp_customize->add_panel(
				$slug,
				array_merge( $default_args, $args )
			);
		}

		/**
		 * Create a section.
		 */
		private function add_section( $slug, $args, $wp_customize ) {
			$default_args = array(
				'capability'  => 'edit_theme_options', //Capability needed to tweak
				'description' => null, //Descriptive tooltip
			);

			$wp_customize->add_section( $slug, array_merge( $default_args, $args ) );
		}

		/**
		 * Create a setting.
		 */
		private function add_setting( $slug, $args, $wp_customize ) {
			$wp_customize->add_setting( $slug,
				array_merge( array(
					'default'           => null, // Default setting/value to save
					'type'              => 'theme_mod', // Is this an 'option' or a 'theme_mod'?
					'capability'        => 'edit_theme_options', // Optional. Special permissions for accessing this setting.
					'transport'         => 'postMessage', // What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
					'sanitize_callback' => 'lsx_sanitize_choices',
				), $args )
			);
		}

		/**
		 * Create a control.
		 */
		private function add_control( $slug, $args, $wp_customize ) {
			$default_args = array();

			if ( isset( $args['control'] ) && class_exists( $args['control'] ) ) {
				$control_class = $args['control'];
				unset( $args['control'] );

				$control = new $control_class( $wp_customize, $slug, array_merge( $default_args, $args ) );
				$wp_customize->add_control( $control );
			} else {
				if ( isset( $args['control'] ) ) {
					unset( $args['control'] );
				}

				$wp_customize->add_control(
					$slug,
					array_merge( $default_args, $args )
				);
			}
		}

		/**
		 * Returns the site title via ajax.
		 */
		public function ajax_site_title() {
			lsx_site_identity();
		}

		/**
		 * Returns a registered field.
		 */
		public function get_control( $id ) {
			$field = $this->controls['fields'][ $id ];
			return $field;
		}

		/**
		 * Returns a registered setting.
		 */
		public function get_setting( $id ) {
			$setting = $this->controls['fields'][ $id ];
			return $setting;
		}

	}

endif;
