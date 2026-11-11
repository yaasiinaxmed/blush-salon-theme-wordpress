<?php
/**
 * Pricing plans.
 *
 * @package Blush_Salon
 */

$blush_plans = blush_get_plans();
if ( ! $blush_plans ) {
	return;
}
?>
<section class="section pricing" aria-labelledby="pricing-title">
	<div class="container">
		<?php blush_heading( blush_mod( 'pricing_eyebrow' ), blush_mod( 'pricing_title' ), 'section-head--center section-head--narrow reveal', 'pricing-title' ); ?>
		<ul class="card-grid card-grid--3 pricing__grid">
			<?php foreach ( $blush_plans as $blush_plan ) : ?>
				<?php
				$blush_btn_url  = $blush_plan['btn_url'] ? $blush_plan['btn_url'] : home_url( '/#book' );
				$blush_btn_text = $blush_plan['btn_text'] ? $blush_plan['btn_text'] : __( 'Get Started', 'blush-salon' );
				?>
				<li class="plan reveal<?php echo $blush_plan['featured'] ? ' plan--featured' : ''; ?>">
					<div class="plan__head">
						<h3 class="plan__title"><?php echo esc_html( $blush_plan['title'] ); ?></h3>
						<?php if ( '' !== (string) $blush_plan['excerpt'] ) : ?>
							<p><?php echo esc_html( $blush_plan['excerpt'] ); ?></p>
						<?php endif; ?>
					</div>
					<p class="plan__price">
						<strong><?php echo esc_html( $blush_plan['price'] ); ?></strong>
						<?php if ( '' !== (string) $blush_plan['period'] ) : ?>
							<span><?php echo esc_html( $blush_plan['period'] ); ?></span>
						<?php endif; ?>
					</p>
					<ul class="check-list check-list--lined">
						<?php foreach ( blush_lines( $blush_plan['features'] ) as $blush_feature ) : ?>
							<li><?php blush_icon( 'check' ); ?><span><?php echo esc_html( $blush_feature ); ?></span></li>
						<?php endforeach; ?>
					</ul>
					<div class="plan__foot">
						<a class="btn btn--block <?php echo $blush_plan['featured'] ? 'btn--accent' : 'btn--outline'; ?>" href="<?php echo esc_url( $blush_btn_url ); ?>">
							<?php echo esc_html( $blush_btn_text ); ?>
							<?php /* translators: %s: plan name. */ ?>
							<span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'with %s', 'blush-salon' ), $blush_plan['title'] ) ); ?></span>
						</a>
						<?php if ( '' !== (string) $blush_plan['note'] ) : ?>
							<p class="plan__note"><?php echo esc_html( $blush_plan['note'] ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
