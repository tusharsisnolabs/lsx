/**
 * LSX Scripts
 *
 * @package    lsx
 * @subpackage scripts
 */

var lsx = Object.create( null );

;( function( $, window, document, undefined ) {

	'use strict';

	var $document    = $( document ),
		$window      = $( window ),
		windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
		windowWidth  = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

	/**
	 * Adds browser class to html tag.
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.add_class_browser_to_html = function() {
		if ( 'undefined' !== typeof platform ) {
			var platform_name = platform.name.toLowerCase(),
				platform_version = platform.version.toLowerCase().replace(/\..*$/g, '');

			$( 'html' ).addClass( platform_name ).addClass( platform_version );
		}
	};

	/**
	 * Test if the sidebar exists (if exists, add a class to the body).
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.add_class_sidebar_to_body = function() {
		if ( $( '#secondary' ).length > 0 ) {
			$( 'body' ).addClass( 'has-sidebar' );
		}
	};

	/**
	 * Add Bootstrap class to WordPress tables.
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.add_class_bootstrap_to_table = function() {
		var tables = $( 'table#wp-calendar' );

		if ( tables.length > 0 ) {
			tables.addClass( 'table' );
		}
	};

	/**
	 * Add a class to identify when the mobile nav is open
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */

	lsx.navbar_toggle_handler = function () {
		$( '.navbar-toggle' ).parent().on( 'click', function () {
			var $parent = $( this );
			$parent.toggleClass( 'open' );
		});
	};

	/**
	 * Fix Bootstrap menus (touchstart).
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	// lsx.fix_bootstrap_menus_touchstart = function() {
	// 	$( '.dropdown-menu' ).on( 'touchstart.dropdown.data-api', function( e ) {
	// 		e.stopPropagation();
	// 	} );
	// };

	/**
	 * Fix Bootstrap menus (dropdown).
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.fix_bootstrap_menus_dropdown = function() {
		$( '.navbar-nav .dropdown, #top-menu .dropdown' ).on( 'show.bs.dropdown', function() {
			if ( windowWidth < 1200 ) {
				$( this ).siblings( '.open' ).removeClass( 'open' ).find( 'a.dropdown-toggle' ).attr( 'data-toggle', 'dropdown' );
				$( this ).find( 'a.dropdown-toggle' ).removeAttr( 'data-toggle' );
			}
		} );

		if ( windowWidth > 1199 ) {
			$( '.navbar-nav li.dropdown a, #top-menu li.dropdown a' ).each( function() {
				$( this ).removeClass( 'dropdown-toggle' );
				$( this ).removeAttr( 'data-toggle' );
			} );
		}

		$window.resize( function() {
			if ( windowWidth > 1199 ) {
				$( '.navbar-nav li.dropdown a, #top-menu li.dropdown a' ).each( function() {
					$( this ).removeClass( 'dropdown-toggle' );
					$( this ).removeAttr( 'data-toggle' );
				} );
			} else {
				$( '.navbar-nav li.dropdown a, #top-menu li.dropdown a' ).each( function() {
					$( this ).addClass( 'dropdown-toggle' );
					$( this ).attr( 'data-toggle', 'dropdown' );
				} );
			}
		} );
	};

	/**
	 * Fix Bootstrap menus (dropdown inside dropdown - click).
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.fix_bootstrap_menus_dropdown_click = function() {
		if ( windowWidth < 1200 ) {
			$( '.navbar-nav .dropdown .dropdown > a, #top-menu .dropdown .dropdown > a' ).on( 'click', function( e ) {
				if ( ! $( this ).parent().hasClass( 'open' ) ) {
					$( this ).parent().addClass( 'open' );
					$( this ).next( '.dropdown-menu' ).dropdown( 'toggle' );
					e.stopPropagation();
					e.preventDefault();
				}
			} );

			$( '.navbar-nav .dropdown .dropdown .dropdown-menu a, #top-menu .dropdown .dropdown > a' ).on( 'click', function( e ) {
				document.location.href = this.href;
			} );
		}
	};

	/**
	 * Fix LazyLoad on Envira
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.fix_lazyload_envira_gallery = function() {
		if ( $( '.lazyload, .lazyloaded' ).length > 0 ) {
			if ( typeof envira_isotopes == 'object' ) {
				$window.scroll( function() {
					$( '.envira-gallery-wrap' ).each( function() {
						var id = $( this ).attr( 'id' );
						id = id.replace( 'envira-gallery-wrap-', '' );

						if ( typeof envira_isotopes[ id ] == 'object' ) {
							envira_isotopes[ id ].enviratope( 'layout' );
						}
					} );
				} );
			}
		}
	};

	/**
	 * Fixed main menu.
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.set_main_menu_as_fixed = function() {
		if ( windowWidth > 1199 ) {
			if ( $( 'body' ).hasClass( 'top-menu-fixed' ) ) {
				$( 'header.navbar' ).scrollToFixed( {
					marginTop: function() {
						var wpadminbar = $( '#wpadminbar' );

						if ( wpadminbar.length > 0 ) {
							return wpadminbar.outerHeight();
						}

						return 0;
					},

					minWidth: 768,

					preFixed: function() {
						$( this ).addClass( 'scrolled' );
					},

					preUnfixed: function() {
						$( this ).removeClass( 'scrolled' );
					}
				} );
			}
		}
	};

	/**
	 * Banner effect (parallax).
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.set_banner_effect_parallax = function() {
		var $banner,
			$bannerImage,
			bannerEndPosition,
			base = -40,

			bannerParallax = function() {
				if ( $window.scrollTop() <= bannerEndPosition ) {
					var scrolled  = $window.scrollTop() / bannerEndPosition * 100,
						top       = base + scrolled,
						bottom    = base - scrolled;

					$bannerImage.css({
						'top': top + 'px',
						'bottom': bottom + 'px'
					});
				}
			};

		if ( windowWidth > 1199 ) {
			$banner = $( '.page-banner:not(.gmap-banner)' );

			if ( $banner.length > 0 ) {
				bannerEndPosition = $banner.height() + $banner.offset().top;
				$bannerImage = $banner.children( '.page-banner-image' );

				if ( $bannerImage.length > 0 ) {
					bannerParallax();

					$window.scroll( function() {
						bannerParallax();
					} );
				}
			}
		}
	};

	/**
	 * Search form effect (on mobile).
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.set_search_form_effect_mobile = function() {
		$document.on( 'click', 'header.navbar #searchform button.search-submit', function( e ) {
			if ( windowWidth < 1200 ) {
				e.preventDefault();
				var form = $( this ).closest( 'form' );

				if ( form.hasClass( 'hover' ) ) {
					form.submit();
				} else {
					form.addClass( 'hover' );
					form.find( '.search-field' ).focus();
				}
			}
		} );

		$document.on( 'blur', 'header.navbar #searchform .search-field', function( e ) {
			if ( windowWidth < 1200 ) {
				var form = $( this ).closest( 'form' );
				form.removeClass( 'hover' );
			}
		} );
	};

	/**
	 * Search form effect (on mobile).
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	lsx.search_form_prevent_empty_submissions = function() {
		$document.on( 'submit', '#searchform', function( e ) {
			if ( '' === $( this ).find('input[name="s"]').val() ) {
				e.preventDefault();
			}
		} );

		$document.on( 'blur', 'header.navbar #searchform .search-field', function( e ) {
			if ( windowWidth < 1200 ) {
				var form = $( this ).closest( 'form' );
				form.removeClass( 'hover' );
			}
		} );
	};

	/**
	 * Init WooCommerce slider.
	 *
	 * @package	lsx
	 * @subpackage scripts
	 */
	lsx.init_wc_slider = function () {
		var $wcSlider = $( '.product_list_widget' );

		$wcSlider.each( function( index, el ) {
			var $self = $( this );

			$self.on( 'init', function( event, slick ) {
				if ( slick.options.arrows && slick.slideCount > slick.options.slidesToShow ) {
					$self.addClass( 'slick-has-arrows' );
				}
			});

			$self.on( 'setPosition', function( event, slick ) {
				if ( ! slick.options.arrows ) {
					$self.removeClass('slick-has-arrows');
				} else if ( slick.slideCount > slick.options.slidesToShow ) {
					$self.addClass('slick-has-arrows');
				}
			});

			$self.slick( {
				draggable: false,
				infinite: true,
				swipe: false,
				cssEase: 'ease-out',
				dots: true,
				slidesToShow: 4,
				slidesToScroll: 4,
				responsive: [{
					breakpoint: 992,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3,
						draggable: true,
						arrows: false,
						swipe: true
					}
				}, {
					breakpoint: 768,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						draggable: true,
						arrows: false,
						swipe: true
					}
				}]
			} );
		} );
	}

	/**
	 * On window resize.
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	$window.resize( function() {

		windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
		windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

	} );

	/**
	 * On document ready.
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	$document.ready( function() {

		lsx.navbar_toggle_handler();

		// lsx.fix_bootstrap_menus_touchstart();

		lsx.add_class_browser_to_html();
		lsx.add_class_sidebar_to_body();
		lsx.add_class_bootstrap_to_table();

		lsx.set_main_menu_as_fixed();

		lsx.init_wc_slider();

	} );

	/**
	 * On window load.
	 *
	 * @package    lsx
	 * @subpackage scripts
	 */
	$window.load( function() {

		lsx.fix_bootstrap_menus_dropdown();
		lsx.fix_bootstrap_menus_dropdown_click();
		lsx.fix_lazyload_envira_gallery();

		lsx.set_search_form_effect_mobile();
		lsx.search_form_prevent_empty_submissions();

		lsx.set_banner_effect_parallax();

		/* LAST CODE TO EXECUTE */
		$( 'body.preloader-content-enable' ).addClass( 'html-loaded' );

	} );

} )( jQuery, window, document );
