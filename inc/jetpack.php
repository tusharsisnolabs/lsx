<?php
/**
 * Jetpack Functionality
 *
 * @package lsx
 * @subpackage jetpack
 */

/*
 * General Jetpack Functionality
 */

/**
 * Adds portfolio and removes pages to the jetpack related posts.
 * 
 * @package lsx
 * @subpackage jetpack
 * @category general
 */
function lsx_allowed_related_post_types($allowed_post_types) {
	$allowed_post_types[] = 'jetpack-portfolio';
	foreach($allowed_post_types as $key => $value){
		if('page' == $value){
			unset($allowed_post_types[$key]);
		}
	}
	return $allowed_post_types;
}

/**
 * Adds the Site Title in Settings->General as a "title" attribute for the logo link.
 * 
 * @package lsx
 * @subpackage jetpack
 * @category site-logo
 */

function lsx_site_logo_title_tag( $html) {

	$html = str_replace('<a', '<a title="'.get_bloginfo('name').'" ', $html);
	return $html;
}
add_filter( 'jetpack_the_site_logo', 'lsx_site_logo_title_tag');


/*
 * Portfolio Functionality
 */

/**
 * Add Featured Image as Banner on Single Portfolio Posts.
 * 
 * @package lsx
 * @subpackage jetpack
 * @category portfolio
 */
function lsx_portfolio_banner() {
	global $post;
	if ( is_singular( 'jetpack-portfolio' ) && has_post_thumbnail() ) {
        $image_src = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>
        <div class="portfolio-banner" style="background-position: center !important; background: url(<?php echo $image_src ?>);">
          <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>   
          </header><!-- .entry-header -->
        </div>
    <?php } 
}
add_action( 'lsx_header_after', 'lsx_portfolio_banner' );

/**
 * Remove the related posts from below the content area.
 * 
 * @package lsx
 * @subpackage jetpack
 * @category portfolio
 */
function lsx_remove_single_portfolio_related_posts() {
	
	if(is_single() && 'jetpack-portfolio' == get_post_type() && class_exists('Jetpack_RelatedPosts')){
		$jprp = Jetpack_RelatedPosts::init();
		$callback = array( $jprp, 'filter_add_target_to_dom' );
		remove_filter( 'the_content', $callback, 40 );
	}
}
add_filter( 'wp', 'lsx_remove_single_portfolio_related_posts', 20 );

/**
 * A template tag to call the Portfolios Related posts
 * 
 * @package lsx
 * @subpackage jetpack
 * @category portfolio
 */
function lsx_portfolio_related_posts(){
	if(class_exists('Jetpack_RelatedPosts')){ ?>
		<div class="row">
			<div class="col-md-12">	
				<?php echo do_shortcode('[jetpack-related-posts]'); ?>
			</div>
		</div>			
	<?php }
}

/**
 * Set the Portfolio to 9 posts per page
 * 
 * @package lsx
 * @subpackage jetpack
 * @category portfolio
 */
function lsx_portfolio_archive_pagination( $query ) {
	if ( $query->is_post_type_archive(array('jetpack-portfolio')) && $query->is_main_query() ) {
		$query->set( 'posts_per_page', get_option( 'jetpack_portfolio_posts_per_page', '9' ) );
	}
}
add_action( 'pre_get_posts', 'lsx_portfolio_archive_pagination' );

/**
 * Remove the sharing from below the content on single portfolio pages.
 *
 * @package lsx
 * @subpackage jetpack
 * @category portfolio
 */
function lsx_portfolio_remove_share() {

	if(is_single() && 'jetpack-portfolio' == get_post_type()){
		remove_filter( 'the_content', 'sharing_display',19 );
		remove_filter( 'the_excerpt', 'sharing_display',19 );
		if ( class_exists( 'Jetpack_Likes' ) ) {
			remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
		}
	}
}

add_action( 'loop_start', 'lsx_portfolio_remove_share' );


/**
 * Redirect the template archive to our one
 * 
 * @package lsx
 * @subpackage jetpack
 * @category portfolio
*/
function lsx_portfolio_taxonomy_template( $template ) {

	if ( is_tax(array('jetpack-portfolio-type','jetpack-portfolio-tag'))  ) {
		$new_template = locate_template( array( 'archive-jetpack-portfolio.php' ) );
		if ( '' != $new_template ) {
			return $new_template ;
		}
	}

	return $template;
}
add_filter( 'template_include', 'lsx_portfolio_taxonomy_template', 99 );

add_action( 'add_meta_boxes', 'lsx_add_portfolio_post_meta_boxes' );
add_action( 'save_post', 'lsx_save_portfolio_post_meta', 100, 2 );

function lsx_save_portfolio_post_meta( $post_id, $post ) {


	if ( (!isset( $_POST['lsx_website_nonce'] ) || !wp_verify_nonce( $_POST['lsx_website_nonce'], basename( __FILE__ ) ))
	|| (!isset( $_POST['lsx_client_nonce'] ) || !wp_verify_nonce( $_POST['lsx_client_nonce'], basename( __FILE__ ) )) )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );

	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;


	$meta_keys = array('lsx-website','lsx-client');

	foreach($meta_keys as $meta_key){

		$new_meta_value = ( isset( $_POST[$meta_key] ) ? sanitize_text_field($_POST[$meta_key]) : '' );

		$meta_value = get_post_meta( $post_id, $meta_key, true );

		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

		elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );

	}
}

function lsx_add_portfolio_post_meta_boxes() {

	add_meta_box(
	'lsx_client_meta_box',
	esc_html__( 'Client', 'lsx' ),
	'lsx_client_meta_box',
	'jetpack-portfolio',
	'side',
	'default'
			);

	add_meta_box(
	'lsx_website_meta_box',
	esc_html__( 'Website', 'lsx' ),
	'lsx_website_meta_box',
	'jetpack-portfolio',
	'side',
	'default'
			);
}

function lsx_client_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'lsx_client_nonce' ); ?>

  <p>
    <input class="widefat" type="text" name="lsx-client" id="lsx-client" value="<?php echo esc_attr( get_post_meta( $object->ID, 'lsx-client', true ) ); ?>" size="30" />
    <br /><br />
    <label for="lsx-client"><?php _e( "Enter the name of the project client", 'client' ); ?></label>
  </p>
<?php }

function lsx_website_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'lsx_website_nonce' ); ?>

  <p>
    <input class="widefat" type="text" name="lsx-website" id="lsx-website" value="<?php echo esc_attr( get_post_meta( $object->ID, 'lsx-website', true ) ); ?>" size="30" />
    <br /><br />
    <label for="lsx-website"><?php _e( "Enter the URL of the project website", 'website' ); ?></label>
  </p>
<?php }


/**
 * A project type filter for the portfolio template
 *
 * @package lsx
 * @subpackage jetpack
 * @category portfolio
 */   

function lsx_portfolio_sorter(){ ?>
	<ul id="filterNav" class="clearfix">
		<li class="allBtn"><a href="#" data-filter="*" class="selected"><?php _e('All', 'themetrust'); ?></a></li>
		<?php $j=1;
		$types = get_terms('jetpack-portfolio-type');
		
		print_r($types);
		if(is_array($types)){
			foreach ($types as $type) {
				$a = '<li><a href="#" data-filter=".'.$type->slug.'">';
		    	$a .= $type->name;					
				$a .= '</a></li>';
				echo $a;
				echo "\n";
				$j++;
			}
		}?>
	</ul>
<?php } 


/*
 * Related Posts
 */

/**
 * Remove the Category from the Jetpack related posts.
 * 
 * @package lsx
 * @subpackage jetpack
 * @category related-posts
 */
function lsx_remove_related_post_context(){
	add_filter( 'jetpack_relatedposts_filter_post_context', '__return_empty_string' );
	add_filter( 'rest_api_allowed_post_types', 'lsx_allowed_related_post_types' );
}
add_action('init','lsx_remove_related_post_context',20);


/**
 * Set the code to be rendered on for calling posts,
 * hooked to template parts when possible.
 * 
 * @package lsx
 * @subpackage jetpack
 * @category infinite scroll
 */
 function lsx_infinite_scroll_render() {
	global $wp_query;
	print_r($wp_query);
	get_template_part( 'content', get_post_type() );
 }