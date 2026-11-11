<?php
/**
 * Services grid.
 *
 * Args: limit (int), show_button (bool).
 *
 * @package Blush_Salon
 */

$blush_args     = isset( $args ) && is_array( $args ) ? $args : array();
$blush_limit    = isset( $blush_args['limit'] ) ? (int) $blush_args['limit'] : -1;
$blush_button   = ! isset( $blush_args['show_button'] ) || $blush_args['show_button'];
$blush_services = blush_get_services( $blush_limit );
?>
<section class="section services" id="services" aria-labelledby="services-title">
	<div class="container">
		<div class="split-head reveal">
			<?php blush_heading( blush_mod( 'services_eyebrow' ), blush_mod( 'services_title' ), '', 'services-title' ); ?>
			<div class="split-head__side">
				<p><?php echo esc_html( blush_mod( 'services_text' ) ); ?></p>
				<?php if ( $blush_button && '' !== blush_mod( 'services_btn_text' ) ) : ?>
					<a class="btn btn--accent" href="<?php echo esc_url( blush_link( 'services_btn_url', 'services', '#services' ) ); ?>"><?php echo esc_html( blush_mod( 'services_btn_text' ) ); ?></a>
				<?php endif; ?>
			</div>
		</div>

		<ul class="service-grid">
			<?php foreach ( $blush_services as $blush_service ) : ?>
				<li class="service-card reveal">
					<img class="service-card__img" src="<?php echo esc_url( $blush_service['image'] ); ?>" alt="" width="800" height="800" loading="lazy">
					<div class="service-card__body">
						<h3 class="service-card__title">
							<?php if ( $blush_service['url'] ) : ?>
								<a href="<?php echo esc_url( $blush_service['url'] ); ?>"><?php echo esc_html( $blush_service['title'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $blush_service['title'] ); ?>
							<?php endif; ?>
						</h3>
						<p><?php echo esc_html( $blush_service['excerpt'] ); ?></p>
						<?php if ( $blush_service['url'] ) : ?>
							<span class="service-card__more" aria-hidden="true"><?php esc_html_e( 'Learn more', 'blush-salon' ); ?><?php blush_icon( 'arrow' ); ?></span>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
