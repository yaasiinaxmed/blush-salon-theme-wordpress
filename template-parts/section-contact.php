<?php
/**
 * Contact details and the "Send us a message" form.
 *
 * @package Blush_Salon
 */

$blush_phones  = array_filter( array( blush_mod( 'phone' ), blush_mod( 'phone_2' ) ), 'strlen' );
$blush_email   = blush_mod( 'email' );
$blush_address = blush_mod( 'address' );
?>
<section class="section contact" aria-labelledby="contact-title">
	<div class="container contact__grid">
		<div class="contact__info reveal">
			<h2 class="section-title section-title--sm" id="contact-title"><?php echo esc_html( blush_mod( 'contact_title' ) ); ?></h2>
			<p><?php echo esc_html( blush_mod( 'contact_text' ) ); ?></p>

			<ul class="feature-list feature-list--plain">
				<?php if ( '' !== $blush_address ) : ?>
					<li class="feature">
						<span class="icon-circle" aria-hidden="true"><?php blush_icon( 'pin' ); ?></span>
						<div>
							<h3 class="feature__title"><?php esc_html_e( 'Head Office', 'blush-salon' ); ?></h3>
							<p><?php echo nl2br( esc_html( $blush_address ) ); ?></p>
						</div>
					</li>
				<?php endif; ?>
				<?php if ( $blush_phones ) : ?>
					<li class="feature">
						<span class="icon-circle" aria-hidden="true"><?php blush_icon( 'phone' ); ?></span>
						<div>
							<h3 class="feature__title"><?php esc_html_e( 'Call Us', 'blush-salon' ); ?></h3>
							<p>
								<?php foreach ( $blush_phones as $blush_phone ) : ?>
									<a href="<?php echo esc_url( blush_tel( $blush_phone ) ); ?>"><?php echo esc_html( $blush_phone ); ?></a><br>
								<?php endforeach; ?>
							</p>
						</div>
					</li>
				<?php endif; ?>
				<?php if ( '' !== $blush_email ) : ?>
					<li class="feature">
						<span class="icon-circle" aria-hidden="true"><?php blush_icon( 'mail' ); ?></span>
						<div>
							<h3 class="feature__title"><?php esc_html_e( 'Email us', 'blush-salon' ); ?></h3>
							<p><a href="<?php echo esc_url( 'mailto:' . antispambot( $blush_email ) ); ?>"><?php echo esc_html( antispambot( $blush_email ) ); ?></a></p>
						</div>
					</li>
				<?php endif; ?>
			</ul>

			<?php if ( blush_socials() ) : ?>
				<div class="contact__social">
					<h3 class="feature__title feature__title--lg"><?php esc_html_e( 'Follow our social network', 'blush-salon' ); ?></h3>
					<?php blush_social_list(); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="contact-card reveal" id="contact-form">
			<h2 class="section-title section-title--sm"><?php echo esc_html( blush_mod( 'contact_form_title' ) ); ?></h2>
			<p><?php echo esc_html( blush_mod( 'contact_form_text' ) ); ?></p>
			<?php blush_form_notice( 'contact' ); ?>
			<form class="form-grid form-grid--white" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php blush_form_hidden_fields( 'contact' ); ?>
				<p class="field">
					<label for="ct-name"><?php esc_html_e( 'Name', 'blush-salon' ); ?> <span class="req" aria-hidden="true">*</span></label>
					<input type="text" id="ct-name" name="blush_name" autocomplete="name" maxlength="100" required>
				</p>
				<p class="field">
					<label for="ct-company"><?php esc_html_e( 'Company', 'blush-salon' ); ?></label>
					<input type="text" id="ct-company" name="blush_company" autocomplete="organization" maxlength="100">
				</p>
				<p class="field">
					<label for="ct-phone"><?php esc_html_e( 'Phone', 'blush-salon' ); ?></label>
					<input type="tel" id="ct-phone" name="blush_phone" autocomplete="tel" maxlength="40">
				</p>
				<p class="field">
					<label for="ct-email"><?php esc_html_e( 'Email', 'blush-salon' ); ?> <span class="req" aria-hidden="true">*</span></label>
					<input type="email" id="ct-email" name="blush_email" autocomplete="email" required>
				</p>
				<p class="field field--full">
					<label for="ct-subject"><?php esc_html_e( 'Subject', 'blush-salon' ); ?></label>
					<input type="text" id="ct-subject" name="blush_subject" maxlength="150">
				</p>
				<p class="field field--full">
					<label for="ct-message"><?php esc_html_e( 'Message', 'blush-salon' ); ?> <span class="req" aria-hidden="true">*</span></label>
					<textarea id="ct-message" name="blush_message" rows="4" maxlength="3000" required></textarea>
				</p>
				<p class="field field--full">
					<button type="submit" class="btn btn--accent btn--block"><?php blush_icon( 'mail' ); ?><?php esc_html_e( 'Send Message', 'blush-salon' ); ?></button>
				</p>
			</form>
		</div>
	</div>
</section>
