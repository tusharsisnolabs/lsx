/**
 * Theme Customizer enhancements for a better user experience.
 *
 * This is the JS that runs on the site in the preview window
 */

( function( $ ) {
	
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

	//Update site background color...
	wp.customize( 'background_color', function( value ) {
		value.bind( function( newval ) {
			$('body').css('background-color', newval );
		} );
	} );
	
	//Update site background color...
	wp.customize( 'lsx_color_scheme', function( value ) {

		value.bind( function( newval ) {
			
			$("<link/>", {
				   rel: "stylesheet",
				   type: "text/css",
				   href: lsx_customizer_params.template_directory+"/css/"+newval+".css"
			}).appendTo("head");
			
		} );
	} );
	
	
	//Update the headers layout.css
    wp.customize("lsx_header_layout", function(value) {
        value.bind(function(newval) {
        	$("body").removeClass('header-central');
        	$("body").removeClass('header-expanded');
            $("body").addClass('header-'+newval);
            console.log($("body"));
        } );
    });	
	

} )( jQuery );
