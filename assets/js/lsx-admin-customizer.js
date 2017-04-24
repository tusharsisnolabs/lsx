/**
 * Theme Customizer enhancements for a better user experience.
 * This is the JS that runs on the site in the preview window.
 */
(function( $ ) {

	// Update the site title in real time...
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( 'h1.site-title a' ).html( newval );
		} );
	} );

	//Update the site description in real time...
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.site-description' ).html( newval );
		} );
	} );

	//Update the headers layout.css
    wp.customize( 'lsx_header_layout', function( value ) {
        value.bind( function( newval ) {
        	$( 'body' ).removeClass( 'header-central' );
        	$( 'body' ).removeClass( 'header-expanded' );
            $( 'body' ).addClass( 'header-'+ newval );
        } );
    });

    //Update the fixed header in real time...
	wp.customize( 'lsx_header_fixed', function( value ) {
		value.bind( function( newval ) {
			if ( true == newval ) {
				$( 'body header.navbar' ).addClass( 'navbar-static-top' );
				$( 'body' ).addClass( 'top-menu-fixed');
			} else {
				$( 'body header.navbar' ).removeClass( 'navbar-static-top' );
				$( 'body' ).removeClass( 'top-menu-fixed' );
			}
		} );
	} );

    //Update the fixed header in real time...
	wp.customize( 'lsx_header_search', function( value ) {
		value.bind( function( newval ) {

			if(true == newval){
				$('body #searchform').show();
			}else{
				$('body #searchform').hide();
			}
		} );
	} );

})( jQuery );
