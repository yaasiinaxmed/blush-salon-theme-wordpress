<?php
/**
 * Front-end forms: appointment booking, contact message, newsletter signup.
 *
 * Every form posts to admin-post.php, is protected by a nonce and a honeypot
 * field, saves an entry in the admin and sends an email to the site owner.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Form definitions: form key => page anchor.
 *
 * @return array
 */
function blush_forms() {
	return array(
		'booking'    => 'book',
		'quick'      => 'quick-booking',
		'contact'    => 'contact-form',
		'newsletter' => 'newsletter',
	);
}

/**
 * Print the hidden fields every form needs.
 *
 * @param string $form Form key from blush_forms().
 */
function blush_form_hidden_fields( $form ) {
	$action = 'quick' === $form ? 'booking' : $form;
	printf( '<input type="hidden" name="action" value="%s">', esc_attr( 'blush_' . $action ) );
	printf( '<input type="hidden" name="blush_form" value="%s">', esc_attr( $form ) );
	wp_nonce_field( 'blush_form_' . $action, 'blush_nonce', true );
	// Honeypot: real visitors never see or fill this field.
	$id = 'blush-hp-' . $form;
	printf(
		'<div class="hp-field" aria-hidden="true"><label for="%1$s">%2$s</label><input type="text" id="%1$s" name="blush_website" value="" tabindex="-1" autocomplete="off"></div>',
		esc_attr( $id ),
		esc_html__( 'Leave this field empty', 'blush-salon' )
	);
}

/**
 * Print the success or error notice for a form after the redirect.
 *
 * @param string $form Form key.
 */
function blush_form_notice( $form ) {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only display flags.
	$current = isset( $_GET['blush_form'] ) ? sanitize_key( wp_unslash( $_GET['blush_form'] ) ) : '';
	$status  = isset( $_GET['blush_status'] ) ? sanitize_key( wp_unslash( $_GET['blush_status'] ) ) : '';
	// phpcs:enable
	if ( $current !== $form || '' === $status ) {
		return;
	}

	$success = array(
		'booking'    => __( 'Thank you! We received your booking request and will contact you soon to confirm it.', 'blush-salon' ),
		'quick'      => __( 'Thank you! We received your booking request and will contact you soon to confirm it.', 'blush-salon' ),
		'contact'    => __( 'Thank you! Your message was sent. We will reply as soon as we can.', 'blush-salon' ),
		'newsletter' => __( 'Thank you! You are now on our newsletter list.', 'blush-salon' ),
	);
	$errors  = array(
		'invalid'  => __( 'Please check the form. Some required fields are empty or not correct.', 'blush-salon' ),
		'email'    => __( 'Please enter a valid email address.', 'blush-salon' ),
		'security' => __( 'The form expired. Please reload the page and try again.', 'blush-salon' ),
		'limit'    => __( 'Too many requests. Please wait a few minutes and try again.', 'blush-salon' ),
		'failed'   => __( 'Sorry, something went wrong. Please try again or call us.', 'blush-salon' ),
	);

	if ( 'success' === $status && isset( $success[ $form ] ) ) {
		printf( '<div class="form-notice form-notice--success" role="status" tabindex="-1">%s</div>', esc_html( $success[ $form ] ) );
	} elseif ( isset( $errors[ $status ] ) ) {
		printf( '<div class="form-notice form-notice--error" role="alert" tabindex="-1">%s</div>', esc_html( $errors[ $status ] ) );
	}
}

/**
 * Redirect back to the page the form was sent from, with a status flag.
 *
 * @param string $form   Form key.
 * @param string $status success or an error code.
 */
function blush_form_redirect( $form, $status ) {
	$forms  = blush_forms();
	$anchor = isset( $forms[ $form ] ) ? $forms[ $form ] : '';
	$url    = wp_get_referer();
	if ( ! $url ) {
		$url = home_url( '/' );
	}
	$url = remove_query_arg( array( 'blush_form', 'blush_status' ), $url );
	$url = add_query_arg(
		array(
			'blush_form'   => $form,
			'blush_status' => $status,
		),
		$url
	);
	wp_safe_redirect( $url . ( $anchor ? '#' . $anchor : '' ) );
	exit;
}

/**
 * Read one posted text field.
 *
 * @param string $key      Field name.
 * @param bool   $textarea Keep line breaks.
 * @return string
 */
function blush_posted( $key, $textarea = false ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce is checked in blush_form_guard() before any call.
	if ( ! isset( $_POST[ $key ] ) || ! is_string( $_POST[ $key ] ) ) {
		return '';
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$value = wp_unslash( $_POST[ $key ] );
	$value = $textarea ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
	return trim( mb_substr( $value, 0, $textarea ? 3000 : 200 ) );
}

/**
 * Shared checks: nonce, honeypot, rate limit. Exits with a redirect when a check fails.
 *
 * @param string $action booking|contact|newsletter.
 * @return string The form key (for the redirect).
 */
function blush_form_guard( $action ) {
	$form = isset( $_POST['blush_form'] ) ? sanitize_key( wp_unslash( $_POST['blush_form'] ) ) : $action; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( ! array_key_exists( $form, blush_forms() ) ) {
		$form = $action;
	}

	$nonce = isset( $_POST['blush_nonce'] ) ? sanitize_key( wp_unslash( $_POST['blush_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'blush_form_' . $action ) ) {
		blush_form_redirect( $form, 'security' );
	}

	// Honeypot filled: almost surely a bot. Pretend it worked and store nothing.
	if ( '' !== blush_posted( 'blush_website' ) ) {
		blush_form_redirect( $form, 'success' );
	}

	// Small rate limit: 8 submissions per 10 minutes per visitor address.
	$ip   = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key  = 'blush_rl_' . md5( $ip . wp_salt( 'nonce' ) );
	$hits = (int) get_transient( $key );
	if ( $hits >= 8 ) {
		blush_form_redirect( $form, 'limit' );
	}
	set_transient( $key, $hits + 1, 10 * MINUTE_IN_SECONDS );

	return $form;
}

/**
 * Address that receives the notification emails.
 *
 * @return string
 */
function blush_notify_address() {
	$email = blush_mod( 'notify_email' );
	return is_email( $email ) ? $email : get_option( 'admin_email' );
}

/**
 * Send a plain-text notification email to the site owner.
 *
 * @param string $subject  Subject.
 * @param array  $rows     Label => value.
 * @param string $reply_to Visitor email (optional).
 * @param string $name     Visitor name (optional).
 * @return bool
 */
function blush_notify( $subject, $rows, $reply_to = '', $name = '' ) {
	$site  = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$lines = array();
	foreach ( $rows as $label => $value ) {
		if ( '' !== (string) $value ) {
			$lines[] = $label . ': ' . $value;
		}
	}
	$lines[] = '';
	/* translators: %s: site URL. */
	$lines[] = sprintf( __( 'Sent from the website %s', 'blush-salon' ), home_url( '/' ) );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $reply_to && is_email( $reply_to ) ) {
		$clean_name = trim( preg_replace( '/[\r\n<>",;:]+/', ' ', $name ) );
		$headers[]  = 'Reply-To: ' . ( $clean_name ? $clean_name . ' <' . $reply_to . '>' : $reply_to );
	}
	return wp_mail( blush_notify_address(), '[' . $site . '] ' . $subject, implode( "\n", $lines ), $headers );
}

/**
 * Handle the appointment booking forms (home quick bar and the full form).
 */
function blush_handle_booking() {
	$form = blush_form_guard( 'booking' );

	$name    = blush_posted( 'blush_name' );
	$email   = sanitize_email( blush_posted( 'blush_email' ) );
	$phone   = blush_posted( 'blush_phone' );
	$date    = blush_posted( 'blush_date' );
	$time    = blush_posted( 'blush_time' );
	$service = blush_posted( 'blush_service' );
	$message = blush_posted( 'blush_message', true );

	if ( '' !== blush_posted( 'blush_email' ) && ! is_email( $email ) ) {
		blush_form_redirect( $form, 'email' );
	}
	$date_ok = (bool) preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $date, $d ) && checkdate( (int) $d[2], (int) $d[3], (int) $d[1] );
	$time_ok = '' === $time || preg_match( '/^([01]\d|2[0-3]):[0-5]\d$/', $time );
	if ( '' === $name || ( '' === $email && '' === $phone ) || ! $date_ok || ! $time_ok ) {
		blush_form_redirect( $form, 'invalid' );
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'blush_appointment',
			'post_status'  => 'publish',
			'post_title'   => trim( $name . ' - ' . $date . ' ' . $time ),
			'post_content' => $message,
			'meta_input'   => array(
				'_blush_name'    => $name,
				'_blush_email'   => $email,
				'_blush_phone'   => $phone,
				'_blush_date'    => $date,
				'_blush_time'    => $time,
				'_blush_service' => $service,
				'_blush_source'  => 'quick' === $form ? __( 'Quick booking bar', 'blush-salon' ) : __( 'Booking form', 'blush-salon' ),
			),
		),
		true
	);
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		blush_form_redirect( $form, 'failed' );
	}

	blush_notify(
		/* translators: %s: visitor name. */
		sprintf( __( 'New appointment request from %s', 'blush-salon' ), $name ),
		array(
			__( 'Name', 'blush-salon' )    => $name,
			__( 'Phone', 'blush-salon' )   => $phone,
			__( 'Email', 'blush-salon' )   => $email,
			__( 'Date', 'blush-salon' )    => $date,
			__( 'Time', 'blush-salon' )    => $time,
			__( 'Service', 'blush-salon' ) => $service,
			__( 'Message', 'blush-salon' ) => $message,
		),
		$email,
		$name
	);

	blush_form_redirect( $form, 'success' );
}
add_action( 'admin_post_nopriv_blush_booking', 'blush_handle_booking' );
add_action( 'admin_post_blush_booking', 'blush_handle_booking' );

/**
 * Handle the contact form.
 */
function blush_handle_contact() {
	$form = blush_form_guard( 'contact' );

	$name    = blush_posted( 'blush_name' );
	$email   = sanitize_email( blush_posted( 'blush_email' ) );
	$phone   = blush_posted( 'blush_phone' );
	$company = blush_posted( 'blush_company' );
	$subject = blush_posted( 'blush_subject' );
	$message = blush_posted( 'blush_message', true );

	if ( ! is_email( $email ) ) {
		blush_form_redirect( $form, 'email' );
	}
	if ( '' === $name || '' === $message ) {
		blush_form_redirect( $form, 'invalid' );
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'blush_message',
			'post_status'  => 'publish',
			'post_title'   => $name . ( $subject ? ' - ' . $subject : '' ),
			'post_content' => $message,
			'meta_input'   => array(
				'_blush_name'    => $name,
				'_blush_email'   => $email,
				'_blush_phone'   => $phone,
				'_blush_company' => $company,
				'_blush_subject' => $subject,
			),
		),
		true
	);
	if ( is_wp_error( $post_id ) || ! $post_id ) {
		blush_form_redirect( $form, 'failed' );
	}

	blush_notify(
		/* translators: %s: visitor name. */
		sprintf( __( 'New message from %s', 'blush-salon' ), $name ),
		array(
			__( 'Name', 'blush-salon' )    => $name,
			__( 'Company', 'blush-salon' ) => $company,
			__( 'Phone', 'blush-salon' )   => $phone,
			__( 'Email', 'blush-salon' )   => $email,
			__( 'Subject', 'blush-salon' ) => $subject,
			__( 'Message', 'blush-salon' ) => $message,
		),
		$email,
		$name
	);

	blush_form_redirect( $form, 'success' );
}
add_action( 'admin_post_nopriv_blush_contact', 'blush_handle_contact' );
add_action( 'admin_post_blush_contact', 'blush_handle_contact' );

/**
 * Handle the newsletter signup.
 */
function blush_handle_newsletter() {
	$form  = blush_form_guard( 'newsletter' );
	$email = sanitize_email( blush_posted( 'blush_email' ) );

	if ( ! is_email( $email ) ) {
		blush_form_redirect( $form, 'email' );
	}

	// Never store the same address twice.
	$existing = get_posts(
		array(
			'post_type'      => 'blush_subscriber',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_blush_email', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $email, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	if ( ! $existing ) {
		$post_id = wp_insert_post(
			array(
				'post_type'   => 'blush_subscriber',
				'post_status' => 'publish',
				'post_title'  => $email,
				'meta_input'  => array( '_blush_email' => $email ),
			),
			true
		);
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			blush_form_redirect( $form, 'failed' );
		}
		blush_notify(
			__( 'New newsletter subscriber', 'blush-salon' ),
			array( __( 'Email', 'blush-salon' ) => $email )
		);
	}

	blush_form_redirect( $form, 'success' );
}
add_action( 'admin_post_nopriv_blush_newsletter', 'blush_handle_newsletter' );
add_action( 'admin_post_blush_newsletter', 'blush_handle_newsletter' );
