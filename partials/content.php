<?php
/**
 * Template used to display post content.
 *
 * @package lsx
 */
?>

<?php lsx_entry_before(); ?>

<?php
	$no_thumb_post_formats = array( 'audio', 'gallery', 'image', 'link', 'quote', 'video' );
	$has_thumb = has_post_thumbnail() && ! has_post_format( $no_thumb_post_formats );

	if ( $has_thumb ) {
		$thumb_class = 'has-thumb';
	} else {
		$thumb_class = 'no-thumb';
	}

	$blog_layout = apply_filters( 'lsx_blog_layout', 'default' );

	$image_class = '';

	$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
	$image_arr    = wp_get_attachment_image_src( $thumbnail_id, 'lsx-thumbnail-single' );

	if ( is_array( $image_arr ) ) {
		$image_src = $image_arr[0];
	}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $thumb_class ); ?>>
	<?php lsx_entry_top(); ?>

	<div class="entry-layout">
		<div class="entry-layout-content entry-layout-content-<?php echo has_post_thumbnail() ? '67' : '100'; ?>">
			<header class="entry-header">
				<?php if ( $has_thumb ) : ?>
					<div class="entry-image <?php echo esc_attr( $image_class ); ?>">
						<a class="thumbnail" href="<?php the_permalink(); ?>">
							<?php lsx_thumbnail( 'lsx-thumbnail-single' ); ?>
						</a>
					</div>
				<?php endif; ?>

				<div class="entry-meta">
					<?php lsx_post_meta_list_top(); ?>
				</div><!-- .entry-meta -->

				<?php
					$format = get_post_format();

					if ( false === $format ) {
						$format = 'standard';
						$show_on_front = get_option( 'show_on_front', 'posts' );

						if ( 'page' === $show_on_front ) {
							$archive_link = get_permalink( get_option( 'page_for_posts' ) );
						} else {
							$archive_link = home_url();
						}
					} else {
						$archive_link = get_post_format_link( $format );
					}

					$format = lsx_translate_format_to_fontawesome( $format );
				?>

				<h1 class="entry-title">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php echo esc_url( $archive_link ); ?>" class="format-link has-thumb fa fa-<?php echo esc_attr( $format ); ?>"></a>
					<?php else : ?>
						<a href="<?php echo esc_url( $archive_link ); ?>" class="format-link fa fa-<?php echo esc_attr( $format ); ?>"></a>
					<?php endif; ?>

					<?php if ( has_post_format( array( 'link' ) ) ) : ?>
						<a href="<?php echo esc_url( lsx_get_my_url() ); ?>" rel="bookmark"><?php the_title(); ?> <span class="fa fa-external-link"></span></a>
					<?php else : ?>
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					<?php endif; ?>

					<?php if ( is_sticky() ) : ?>
						<span class="label label-default label-sticky"><?php esc_html_e( 'Featured', 'lsx' ); ?></span>
					<?php endif; ?>
				</h1>
			</header><!-- .entry-header -->

			<?php if ( has_post_format( array( 'quote' ) ) || apply_filters( 'lsx_blog_display_text_on_list', true ) ) : ?>

				<?php if ( ! has_post_format( array( 'video', 'audio', 'quote', 'link' ) ) && ! apply_filters( 'lsx_blog_force_content_on_list', false ) ) : ?>

					<div class="entry-summary">
						<?php the_excerpt(); ?>
						<?php edit_post_link( esc_html__( 'Edit', 'lsx' ), '<p class="edit-link">', '</p>' ); ?>
					</div><!-- .entry-summary -->

				<?php elseif ( has_post_format( array( 'link' ) ) ) : ?>

				<?php elseif ( apply_filters( 'lsx_blog_force_content_on_list', false ) ) : ?>

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->

				<?php else : ?>

					<div class="entry-content">
						<?php
							the_content();

							wp_link_pages( array(
								'before'      => '<div class="lsx-postnav-wrapper"><div class="lsx-postnav">',
								'after'       => '</div></div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
							) );
						?>
					</div><!-- .entry-content -->

				<?php endif; ?>

			<?php endif; ?>

			<div class="clearfix"></div>

			<?php $comments_number = get_comments_number(); ?>

			<div class="post-tags-wrapper">
				<?php lsx_post_meta_category(); ?>

				<?php lsx_content_post_tags(); ?>

				<?php if ( comments_open() && ! empty( $comments_number ) ) : ?>
					<div class="post-comments">
						<a href="<?php the_permalink(); ?>#comments">
							<?php
								if ( '1' === $comments_number ) {
									echo esc_html( _x( 'One Comment', 'lsx' ) );
								} else {
									printf(
										/* Translators: %s: number of comments */
										esc_html( _nx(
											'%s Comment',
											'%s Comments',
											$comments_number,
											'content.php',
											'lsx'
										) ),
										esc_html( number_format_i18n( $comments_number ) )
									);
								}
							?>
						</a>
					</div>
				<?php endif ?>
			</div>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>

			<div class="entry-image hidden-xs">
				<a class="thumbnail" href="<?php the_permalink(); ?>" style="background-image:url(<?php echo esc_url( $image_src ); ?>);">
					<?php lsx_thumbnail( 'lsx-thumbnail-single' ); ?>
				</a>
			</div>

		<?php endif; ?>
	</div>

	<?php lsx_entry_bottom(); ?>

	<div class="clearfix"></div>

	<div class="lsx-breaker"></div>
</article>

<?php lsx_entry_after();
