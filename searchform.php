<?php
/**
 * The template for displaying search forms in lsx
 *
 * @package lsx
 */

//This is to help the customizer function better
$style = '';

if ( is_customize_preview() ) {
	$search_form = get_theme_mod( 'lsx_header_search', 0 );
	
	if ( ! $search_form ) {
		$style = 'style="display:none;"';
	}
}
?>

<form role="search" method="get" <?php echo esc_attr( $style ); ?> class="search-form form-inline" id="searchform" action="<?php echo home_url( '/' ); ?>">
	<div class="input-group">
		<input type="search" value="<?php if ( is_search() ) { echo get_search_query(); } ?>" name="s" class="search-field form-control" placeholder="<?php esc_attr_e( 'Search', 'lsx' ); ?> <?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<label class="hide"><?php esc_html_e( 'Search for:', 'lsx' ); ?></label>
		
		<span class="input-group-btn">
			<button type="submit" class="search-submit btn btn-default"><span class="fa fa-search"></span></button>
		</span>
	</div>
</form>
