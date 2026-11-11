<?php
/**
 * "Who we are" section with image collage and experience badge.
 *
 * @package Blush_Salon
 */

$blush_features = array(
	array( 'shop', blush_mod( 'about_f1_title' ), blush_mod( 'about_f1_text' ) ),
	array( 'sparkle', blush_mod( 'about_f2_title' ), blush_mod( 'about_f2_text' ) ),
);
?>
<section class="section about" aria-labelledby="about-title">
	<div class="container about__grid">
		<div class="collage reveal">
			<?php for ( $blush_i = 1; $blush_i <= 4; $blush_i++ ) : ?>
				<img class="collage__img" src="<?php echo esc_url( blush_img( 'about_img_' . $blush_i, 'about-' . $blush_i . '.svg' ) ); ?>" alt="" width="800" height="640" loading="lazy">
			<?php endfor; ?>
			<?php if ( '' !== blush_mod( 'years_number' ) ) : ?>
				<p class="collage__badge">
					<strong><?php echo esc_html( blush_mod( 'years_number' ) ); ?></strong>
					<span><?php echo esc_html( blush_mod( 'years_label' ) ); ?></span>
				</p>
			<?php endif; ?>
		</div>

		<div class="about__content reveal">
			<?php blush_heading( blush_mod( 'about_eyebrow' ), blush_mod( 'about_title' ), '', 'about-title' ); ?>
			<p class="lead"><?php echo esc_html( blush_mod( 'about_text' ) ); ?></p>
			<ul class="feature-list">
				<?php foreach ( $blush_features as $blush_feature ) : ?>
					<?php if ( '' !== $blush_feature[1] ) : ?>
						<li class="feature">
							<span class="icon-circle" aria-hidden="true"><?php blush_icon( $blush_feature[0] ); ?></span>
							<div>
								<h3 class="feature__title"><?php echo esc_html( $blush_feature[1] ); ?></h3>
								<p><?php echo esc_html( $blush_feature[2] ); ?></p>
							</div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
