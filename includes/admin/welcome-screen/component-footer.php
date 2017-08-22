<?php
/**
 * Welcome screen contribute template
 *
 * @package lxs
 */

?>

<div class="row">
	<div class="col-md-4">
		<div class="box mailchimp">
			<h2><?php esc_html_e( 'Newsletter', 'lsx' ); ?></h2>
			<p><?php esc_html_e( 'Subscribe to our mailing list.', 'lsx' ); ?></p>

			<!-- Begin MailChimp Signup Form -->
			<form action="//lsdev.us2.list-manage.com/subscribe/post?u=e50b2c5c82f4b42ea978af479&amp;id=92c36218e5" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup">
					<div id="mc_embed_signup_scroll">
						<div class="mc-field-group">
							<label for="mce-EMAIL"><?php esc_html_e( 'Email Address', 'lsx' ); ?> <span class="asterisk">*</span></label>
							<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
						</div>
						<div class="mc-field-group">
							<label for="mce-FNAME"><?php esc_html_e( 'First Name', 'lsx' ); ?> </label>
							<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
						</div>
						<div class="mc-field-group">
							<label for="mce-LNAME"><?php esc_html_e( 'Last Name', 'lsx' ); ?> </label>
							<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
						</div>
					</div>
					<div style="position:absolute;left:-5000px;" aria-hidden="true"><input type="text" name="b_e50b2c5c82f4b42ea978af479_92c36218e5" tabindex="-1" value=""></div>
				</div>
				<div class="more-button">
					<input type="submit" value="<?php esc_html_e( 'Subscribe', 'lsx' ); ?>" name="subscribe" class="button button-primary">
				</div>
			</form>
			<!--End mc_embed_signup-->
		</div>
	</div>

	<div class="col-md-4">
		<div class="box support">
			<h2><?php esc_html_e( 'Get support', 'lsx' ); ?></h2>

			<p>
				<?php
					printf(
						/* Translators: 1: HTML open tag link, 2: HTML close tag link, 3: HTML open tag link, 4: HTML close tag link */
						esc_html__( 'You\'ll find information on how to use and customize the LSX theme in our %1$sdocumentation%2$s section. However, please do %3$scontact us%4$s for support should you still find yourself unable to achieve your needs.', 'lsx' ),
						'<a href="https://www.lsdev.biz/documentation/lsx/" target="_blank">',
						'</a>',
						'<a href="https://www.lsdev.biz/contact-us/" target="_blank">',
						'</a>'
					);
				?>
			</p>

			<div class="more-button">
				<a href="https://www.lsdev.biz/contact-us/" target="_blank" class="button button-primary">
					<?php esc_html_e( 'Get in touch', 'lsx' ); ?>
				</a>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="box suggest">
			<h2><?php esc_html_e( 'Suggest a feature', 'lsx' ); ?></h2>

			<p>
				<?php
					printf(
						/* Translators: 1: HTML open tag link, 2: HTML close tag link */
						esc_html__( 'If you\'d like to suggest a feature for inclusion in upcoming releases of the LSX theme, please don\'t hesitate to %1$scontact us%2$s directly. We\'re always on the lookout for fresh ideas to help make LSX even better.', 'lsx' ),
						'<a href="https://www.lsdev.biz/contact-us/" target="_blank">',
						'</a>'
					);
				?>
			</p>

			<div class="more-button">
				<a href="https://www.lsdev.biz/contact-us/" target="_blank" class="button button-primary">
					<?php esc_html_e( 'Submit a request', 'lsx' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
