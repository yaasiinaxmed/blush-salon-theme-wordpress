<?php
/**
 * Testimonials carousel.
 *
 * @package Blush_Salon
 */

$blush_items = blush_get_testimonials();
if ( ! $blush_items ) {
	return;
}
?>
<section class="section testimonials" aria-labelledby="testimonials-title">
	<div class="container">
		<?php blush_heading( blush_mod( 'testi_eyebrow' ), blush_mod( 'testi_title' ), 'section-head--center reveal', 'testimonials-title' ); ?>

		<div class="carousel reveal" data-carousel>
			<ul class="carousel__track" data-carousel-track tabindex="0" aria-label="<?php esc_attr_e( 'Customer reviews. Scroll or swipe to see more.', 'blush-salon' ); ?>">
				<?php foreach ( $blush_items as $blush_item ) : ?>
					<li class="carousel__slide">
						<figure class="testimonial">
							<blockquote><?php echo wp_kses_post( wpautop( $blush_item['quote'] ) ); ?></blockquote>
							<figcaption>
								<img src="<?php echo esc_url( $blush_item['image'] ); ?>" alt="" width="84" height="84" loading="lazy">
								<span class="testimonial__name"><?php echo esc_html( $blush_item['name'] ); ?></span>
								<?php if ( '' !== (string) $blush_item['city'] ) : ?>
									<span class="testimonial__city"><?php echo esc_html( $blush_item['city'] ); ?></span>
								<?php endif; ?>
							</figcaption>
						</figure>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="carousel__dots" data-carousel-dots></div>
		</div>
	</div>
</section>
