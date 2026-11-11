<?php
/**
 * "Why Choose Us" gradient banner with four features.
 *
 * @package Blush_Salon
 */

$blush_icons = array( 1 => 'leaf', 2 => 'diamond', 3 => 'scissors', 4 => 'sparkle' );
?>
<section class="why" style="background-image:url('<?php echo esc_url( blush_img( 'why_image', 'why-bg.svg' ) ); ?>')" aria-labelledby="why-title">
	<div class="container why__inner">
		<div class="why__content reveal">
			<?php blush_heading( blush_mod( 'why_eyebrow' ), blush_mod( 'why_title' ), 'section-head--light', 'why-title' ); ?>
			<p class="lead"><?php echo esc_html( blush_mod( 'why_text' ) ); ?></p>
			<ul class="why__features">
				<?php foreach ( $blush_icons as $blush_i => $blush_icon_name ) : ?>
					<?php if ( '' !== blush_mod( 'why_f' . $blush_i . '_title' ) ) : ?>
						<li class="feature feature--light">
							<span class="icon-circle icon-circle--white icon-circle--lg" aria-hidden="true"><?php blush_icon( $blush_icon_name ); ?></span>
							<div>
								<h3 class="feature__title"><?php echo esc_html( blush_mod( 'why_f' . $blush_i . '_title' ) ); ?></h3>
								<p><?php echo esc_html( blush_mod( 'why_f' . $blush_i . '_text' ) ); ?></p>
							</div>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
