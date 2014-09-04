<?php
/**
 * @package lsx
 */
?>

<?php lsx_entry_before(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php lsx_entry_top(); ?>
	
	<?php if ( is_single() ) { ?>
	<header class="page-header">
		<h1 class="page-title"><?php the_title(); ?></h1>		
	</header><!-- .entry-header -->
	<?php } else { ?>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>		
	</header><!-- .entry-header -->
	<?php } ?>
	<?php lsx_post_meta(); ?>
	<?php echo get_the_tag_list('<p><i class="fa fa-tags"></i> ',', ','</p>'); ?>

	<div class="entry-content">
		<?php if ( lsx_get_option( 'single_thumbnails') && has_post_thumbnail() ) {
			the_post_thumbnail( 'thumbnail-single' );
		} ?>
		<?php if ( ! is_singular() ) {
			the_excerpt();
		} else {
			the_content(); 
		} ?>
		<?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'lsx'), 'after' => '</p></nav>')); ?>
	</div><!-- .entry-content -->

		<?php edit_post_link( __( 'Edit', 'lsx' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

	<?php lsx_entry_bottom(); ?>

</article><!-- #post-## -->

<?php lsx_entry_after(); ?>