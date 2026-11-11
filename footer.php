<?php
/**
 * Site footer.
 *
 * @package Blush_Salon
 */

$blush_columns = array(
	'footer-company' => __( 'Company', 'blush-salon' ),
	'footer-support' => __( 'Support', 'blush-salon' ),
);
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<?php blush_logo(); ?>
			<?php if ( '' !== blush_mod( 'footer_blurb' ) ) : ?>
				<p><?php echo esc_html( blush_mod( 'footer_blurb' ) ); ?></p>
			<?php endif; ?>
			<?php blush_social_list( null, 'social-list--light' ); ?>
		</div>

		<?php foreach ( $blush_columns as $blush_location => $blush_title ) : ?>
			<?php if ( has_nav_menu( $blush_location ) ) : ?>
				<nav class="site-footer__menu" aria-label="<?php echo esc_attr( $blush_title ); ?>">
					<h2 class="site-footer__title"><?php echo esc_html( $blush_title ); ?></h2>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $blush_location,
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>
		<?php endforeach; ?>

		<section class="newsletter" id="newsletter" aria-labelledby="newsletter-title">
			<span class="newsletter__icon" aria-hidden="true"><?php blush_icon( 'mail' ); ?></span>
			<h2 class="newsletter__title" id="newsletter-title"><?php echo esc_html( blush_mod( 'newsletter_title' ) ); ?></h2>
			<p><?php echo esc_html( blush_mod( 'newsletter_text' ) ); ?></p>
			<?php blush_form_notice( 'newsletter' ); ?>
			<form class="newsletter__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php blush_form_hidden_fields( 'newsletter' ); ?>
				<label class="screen-reader-text" for="newsletter-email"><?php esc_html_e( 'Email address', 'blush-salon' ); ?></label>
				<input type="email" id="newsletter-email" name="blush_email" placeholder="<?php esc_attr_e( 'Email', 'blush-salon' ); ?>" autocomplete="email" required>
				<button type="submit" class="btn btn--accent btn--block"><?php blush_icon( 'mail' ); ?><?php esc_html_e( 'Sign up', 'blush-salon' ); ?></button>
			</form>
		</section>
	</div>

	<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
		<div class="container site-footer__widgets">
			<?php dynamic_sidebar( 'footer-1' ); ?>
		</div>
	<?php endif; ?>

	<div class="site-footer__bar">
		<div class="container site-footer__bar-inner">
			<p><?php echo esc_html( blush_copyright() ); ?></p>
			<?php
			if ( has_nav_menu( 'footer-legal' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'footer-legal',
						'container'      => 'nav',
						'container_aria_label' => __( 'Legal links', 'blush-salon' ),
						'menu_class'     => 'legal-menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} elseif ( function_exists( 'the_privacy_policy_link' ) ) {
				the_privacy_policy_link( '<p class="legal-menu">', '</p>' );
			}
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
