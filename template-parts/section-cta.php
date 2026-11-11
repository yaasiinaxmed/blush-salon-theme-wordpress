<?php
/**
 * Gradient call-to-action banner.
 *
 * @package Blush_Salon
 */

?>
<section class="cta-banner" style="background-image:url('<?php echo esc_url( blush_img( 'cta_image', 'cta-makeup.svg' ) ); ?>')" aria-labelledby="cta-title">
	<div class="container cta-banner__inner">
		<div class="cta-banner__content reveal">
			<h2 class="cta-banner__title" id="cta-title"><?php echo esc_html( blush_mod( 'cta_title' ) ); ?></h2>
			<p><?php echo esc_html( blush_mod( 'cta_text' ) ); ?></p>
			<?php if ( '' !== blush_mod( 'cta_btn_text' ) ) : ?>
				<a class="btn btn--dark" href="<?php echo esc_url( blush_link( 'cta_btn_url', 'services', '#services' ) ); ?>"><?php echo esc_html( blush_mod( 'cta_btn_text' ) ); ?><?php blush_icon( 'arrow' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
