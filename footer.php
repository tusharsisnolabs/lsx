<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package lsx
 */
?>
	<?php if ( ! is_singular( 'landing-page' ) ) : ?>

			</div><!-- .content -->
		</div><!-- wrap -->

		<?php lsx_footer_before(); ?>

		<footer class="content-info">
			<div class="container">
				<div class="row">
						<div class="col-sm-12">
							<div class="footer-menu">
								<?php
									if ( ! is_user_logged_in() ) {
										if ( has_nav_menu( 'footer-menu' ) ) {
											wp_nav_menu( array( 'theme_location' => 'footer-menu' ) );
										}
									} else {
										if ( has_nav_menu( 'footer_logged_in' ) ) {
											wp_nav_menu( array( 'theme_location' => 'footer_logged_in' ) );
										}
									}
								?>
								<div class="clearfix"></div>
							</div>
						</div>
				 </div>

				<div class="row">
					<div class="col-sm-12">

						<?php lsx_footer_top(); ?>

						<p class="credit <?php if ( has_nav_menu( 'social' ) || has_nav_menu( 'footer' ) ) { ?>credit-float<?php } ?>"><?php printf( esc_html__( '&#169; %1$s %2$s All Rights Reserved.', 'lsx' ), esc_html( date_i18n( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) ); ?></p>
						
						<?php if ( has_nav_menu( 'social' ) ) { ?>
							<nav id="social-navigation" class="social-navigation">
								<?php
									// Social links navigation menu.
									wp_nav_menu( array(
										'theme_location' => 'social',
										'depth'          => 1,
									) );
								?>
							</nav><!-- .social-navigation -->
						<?php } ?>

						<?php if ( has_nav_menu( 'footer' ) ) { ?>
							<nav id="footer-navigation" class="footer-navigation">
								<?php
									// Footer links navigation menu.
									wp_nav_menu( array(
										'theme_location' => 'footer',
										'depth'          => 1,
									) );
								?>
							</nav><!-- .footer-navigation -->
						<?php } ?>

						<?php lsx_footer_bottom(); ?>

					</div>
				</div>
			</div>
		</footer>

		<?php lsx_footer_after(); ?>

		<?php wp_footer(); ?> 

	<?php else : ?>
	
		<?php lsx_footer_before(); ?>

		<footer class="content-info">
			<div class="container">
				<div class="row">
					<div class="col-sm-12">

						<?php lsx_footer_top(); ?>

						<p class="credit <?php if ( has_nav_menu( 'social' ) ) { ?>credit-float<?php } ?>"><?php printf( esc_html__( '&#169; %1$s %2$s All Rights Reserved.', 'lsx' ), esc_html( date_i18n( 'Y' ) ), esc_html( get_bloginfo( 'name' ) ) ); ?></p>
						
						<?php if ( has_nav_menu( 'social' ) ) : ?>
							<nav id="social-navigation" class="social-navigation" role="navigation">
								<?php
									// Social links navigation menu.
									wp_nav_menu( array(
										'theme_location' => 'social',
										'depth'		     => 1,
									) );
								?>
							</nav><!-- .social-navigation -->
						<?php endif; ?>

						<?php lsx_footer_bottom(); ?>

					</div>
				</div>
			</div>
		</footer>

		<?php lsx_footer_after(); ?>

		<?php wp_footer(); ?>

	<?php endif; ?>

	<?php lsx_body_bottom(); ?>
</body>
</html>
