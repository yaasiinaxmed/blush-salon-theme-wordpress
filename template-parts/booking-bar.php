<?php
/**
 * Quick booking bar under the home hero.
 *
 * @package Blush_Salon
 */

$blush_phone = blush_mod( 'phone' );
?>
<section class="booking-bar" id="quick-booking" aria-label="<?php esc_attr_e( 'Quick booking', 'blush-salon' ); ?>">
	<div class="container booking-bar__inner">
		<div class="booking-bar__form-wrap">
			<?php blush_form_notice( 'quick' ); ?>
			<form class="booking-bar__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php blush_form_hidden_fields( 'quick' ); ?>
				<p class="field">
					<label for="qb-name"><?php esc_html_e( 'Name', 'blush-salon' ); ?></label>
					<input type="text" id="qb-name" name="blush_name" placeholder="<?php esc_attr_e( 'Your name', 'blush-salon' ); ?>" autocomplete="name" maxlength="100" required>
				</p>
				<p class="field">
					<label for="qb-email"><?php esc_html_e( 'Email', 'blush-salon' ); ?></label>
					<input type="email" id="qb-email" name="blush_email" placeholder="<?php esc_attr_e( 'you@example.com', 'blush-salon' ); ?>" autocomplete="email" required>
				</p>
				<p class="field">
					<label for="qb-date"><?php esc_html_e( 'Date', 'blush-salon' ); ?></label>
					<input type="date" id="qb-date" name="blush_date" min="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>" required>
				</p>
				<p class="field">
					<label for="qb-time"><?php esc_html_e( 'Time', 'blush-salon' ); ?></label>
					<input type="time" id="qb-time" name="blush_time" required>
				</p>
				<p class="field">
					<label for="qb-service"><?php esc_html_e( 'Service', 'blush-salon' ); ?></label>
					<select id="qb-service" name="blush_service">
						<?php foreach ( blush_service_options() as $blush_option ) : ?>
							<option value="<?php echo esc_attr( $blush_option ); ?>"><?php echo esc_html( $blush_option ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<p class="field field--submit">
					<button type="submit" class="btn btn--accent btn--block"><?php blush_icon( 'calendar' ); ?><?php esc_html_e( 'Book Now', 'blush-salon' ); ?></button>
				</p>
			</form>
		</div>

		<?php if ( '' !== $blush_phone ) : ?>
			<div class="phone-box">
				<span class="icon-circle icon-circle--lg" aria-hidden="true"><?php blush_icon( 'phone' ); ?></span>
				<p>
					<span class="phone-box__label"><?php esc_html_e( 'Need help? Contact us', 'blush-salon' ); ?></span>
					<a class="phone-box__number" href="<?php echo esc_url( blush_tel( $blush_phone ) ); ?>"><?php echo esc_html( $blush_phone ); ?></a>
				</p>
			</div>
		<?php endif; ?>
	</div>
</section>
