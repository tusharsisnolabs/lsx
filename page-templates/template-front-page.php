<?php
/* Template Name: Front Page */

get_header(); ?>

	<?php lsx_content_wrap_before(); ?>
	
	<section id="primary" class="content-area front-page col-sm-12">
	
		<?php lsx_content_before(); ?>
		
		<main id="main" class="site-main">

			<?php lsx_content_top(); ?>		
			
			<?php if(have_posts() && !class_exists('Lsx_Banners')) : ?>
			
				<?php while ( have_posts() ) : the_post(); ?>
				
					<?php lsx_entry_before(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
							<div class="entry-content">
								<?php the_content(); ?>
								<?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
							</div><!-- .entry-content -->
						
							<?php lsx_entry_bottom(); ?>
							
						</article><!-- #post-## -->
	
				<?php endwhile; // end of the loop. ?>			
			
			<?php endif; ?>
			
			<section id="home-widgets">
			
				<?php if ( ! dynamic_sidebar( 'sidebar-home' ) ) : ?>
				
				
				<?php endif; // end sidebar widget area ?>
				
			</section>		
				
			<?php lsx_content_bottom(); ?>
		
		</main><!-- #main -->
		
		<?php lsx_content_after(); ?>

	</section><!-- #primary -->

	<?php lsx_content_wrap_after(); ?>
	
<?php get_footer();