<?php
/**
 * Template Name: Archives
 *
 * @package lsx
 */

get_header(); ?>

	<?php lsx_content_wrap_before(); ?>

	<div id="primary" class="content-area <?php echo esc_attr( lsx_main_class() ); ?>">

		<?php lsx_content_before(); ?>

		<main id="main" class="site-main">

			<?php lsx_content_top(); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php lsx_entry_before(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php lsx_entry_top(); ?>

					<div class="entry-content">
						<h2><?php esc_html_e( 'The Last 30 Posts', 'lsx' ); ?></h3>
						
						<ul>
							<?php $loop = new WP_Query( array( 'posts_per_page' => 30 ) ); ?>
							
							<?php if ( $loop->have_posts() ) : ?>
								
								<?php while ( $loop->have_posts() ) : ?>
									
									<?php
										$loop->the_post();
										$loop->is_home = false;
									?>
									
									<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php echo esc_html( $post->comment_count ); ?> <?php esc_html_e( 'comments', 'lsx' ); ?></li>
								
								<?php endwhile; ?>

							<?php endif; ?>

							<?php wp_reset_postdata(); ?>
						</ul>

						<h2><?php esc_html_e( 'Categories', 'lsx' ); ?></h3>

						<ul>
							<?php wp_list_categories( 'title_li=&hierarchical=0&show_count=1' ); ?>
						</ul>

						<h2><?php esc_html_e( 'Monthly Archives', 'lsx' ); ?></h3>

						<ul>
							<?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>
						</ul>
					</div><!-- .entry-content -->
					
					<?php edit_post_link( esc_html__( 'Edit', 'lsx' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>

					<?php lsx_entry_bottom(); ?>
					
				</article><!-- #post-## -->

				<?php lsx_entry_after(); ?>

			<?php endwhile; ?>

			<?php lsx_content_bottom(); ?>

		</main><!-- #main -->

		<?php lsx_content_after(); ?>
		
	</div><!-- #primary -->

	<?php lsx_content_wrap_after(); ?>

<?php get_sidebar(); ?>

<?php get_footer();
