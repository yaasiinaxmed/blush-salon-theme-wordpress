<?php
/**
 * Partner logos row.
 *
 * @package Blush_Salon
 */

?>
<section class="section section--line logos" aria-labelledby="logos-title">
	<div class="container logos__grid">
		<div class="logos__content reveal">
			<h2 class="section-title" id="logos-title"><?php echo esc_html( blush_mod( 'logos_title' ) ); ?></h2>
			<p><?php echo esc_html( blush_mod( 'logos_text' ) ); ?></p>
		</div>
		<ul class="logos__list reveal">
			<?php for ( $blush_i = 1; $blush_i <= 8; $blush_i++ ) : ?>
				<li>
					<?php /* translators: %d: logo number. */ ?>
					<img src="<?php echo esc_url( blush_img( 'logo_' . $blush_i, 'logo-' . $blush_i . '.svg' ) ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Partner logo %d', 'blush-salon' ), $blush_i ) ); ?>" width="240" height="60" loading="lazy">
				</li>
			<?php endfor; ?>
		</ul>
	</div>
</section>
