<?php
/**
 * "Book an Appointment" section: benefits, phone numbers and the booking form.
 *
 * @package Blush_Salon
 */

$blush_benefits = blush_lines( blush_mod( 'booking_benefits' ) );
$blush_phones   = array_filter( array( blush_mod( 'phone' ), blush_mod( 'phone_2' ) ), 'strlen' );
?>
<section class="section section--grey appointment" aria-labelledby="booking-title">
	<div class="container appointment__grid">
		<div class="appointment__content reveal">
			<?php blush_heading( blush_mod( 'booking_eyebrow' ), blush_mod( 'booking_title' ), '', 'booking-title' ); ?>
			<p class="lead"><?php echo esc_html( blush_mod( 'booking_text' ) ); ?></p>

			<?php if ( $blush_benefits ) : ?>
				<div class="benefits">
					<h3 class="benefits__title"><?php esc_html_e( 'Benefits:', 'blush-salon' ); ?></h3>
					<ul class="check-list check-list--cols">
						<?php foreach ( $blush_benefits as $blush_benefit ) : ?>
							<li><?php blush_icon( 'check' ); ?><span><?php echo esc_html( $blush_benefit ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( $blush_phones ) : ?>
				<div class="phone-row">
					<?php foreach ( $blush_phones as $blush_phone ) : ?>
						<div class="phone-box">
							<span class="icon-circle icon-circle--lg" aria-hidden="true"><?php blush_icon( 'phone' ); ?></span>
							<p>
								<span class="phone-box__label"><?php esc_html_e( 'Customer Support', 'blush-salon' ); ?></span>
								<a class="phone-box__number" href="<?php echo esc_url( blush_tel( $blush_phone ) ); ?>"><?php echo esc_html( $blush_phone ); ?></a>
							</p>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="form-card reveal" id="book">
			<h2 class="form-card__title"><?php echo esc_html( blush_mod( 'booking_form_title' ) ); ?></h2>
			<div class="form-card__body">
				<?php blush_form_notice( 'booking' ); ?>
				<form class="form-grid" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php blush_form_hidden_fields( 'booking' ); ?>
					<p class="field">
						<label for="bk-name"><?php esc_html_e( 'Full Name', 'blush-salon' ); ?> <span class="req" aria-hidden="true">*</span></label>
						<input type="text" id="bk-name" name="blush_name" autocomplete="name" maxlength="100" required>
					</p>
					<p class="field">
						<label for="bk-phone"><?php esc_html_e( 'Phone', 'blush-salon' ); ?> <span class="req" aria-hidden="true">*</span></label>
						<input type="tel" id="bk-phone" name="blush_phone" autocomplete="tel" maxlength="40" required>
					</p>
					<p class="field field--full">
						<label for="bk-email"><?php esc_html_e( 'Email', 'blush-salon' ); ?></label>
						<input type="email" id="bk-email" name="blush_email" autocomplete="email">
					</p>
					<p class="field">
						<label for="bk-date"><?php esc_html_e( 'Date', 'blush-salon' ); ?> <span class="req" aria-hidden="true">*</span></label>
						<input type="date" id="bk-date" name="blush_date" min="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>" required>
					</p>
					<p class="field">
						<label for="bk-time"><?php esc_html_e( 'Time', 'blush-salon' ); ?> <span class="req" aria-hidden="true">*</span></label>
						<input type="time" id="bk-time" name="blush_time" required>
					</p>
					<p class="field field--full">
						<label for="bk-service"><?php esc_html_e( 'Service', 'blush-salon' ); ?></label>
						<select id="bk-service" name="blush_service">
							<?php foreach ( blush_service_options() as $blush_option ) : ?>
								<option value="<?php echo esc_attr( $blush_option ); ?>"><?php echo esc_html( $blush_option ); ?></option>
							<?php endforeach; ?>
						</select>
					</p>
					<p class="field field--full">
						<label for="bk-message"><?php esc_html_e( 'Message', 'blush-salon' ); ?></label>
						<textarea id="bk-message" name="blush_message" rows="4" maxlength="3000"></textarea>
					</p>
					<p class="field field--full">
						<button type="submit" class="btn btn--accent btn--block"><?php blush_icon( 'calendar' ); ?><?php esc_html_e( 'Book Now!', 'blush-salon' ); ?></button>
					</p>
				</form>
			</div>
		</div>
	</div>
</section>
