<?php
/**
 * Simple meta boxes (no plugin needed).
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Editable meta fields per post type: meta key => array( label, type, help ).
 * Types: text, url, textarea, checkbox.
 *
 * @param string $post_type Post type.
 * @return array
 */
function blush_meta_fields( $post_type ) {
	$fields = array(
		'blush_service'     => array(
			'_blush_link' => array( __( 'Custom link (optional)', 'blush-salon' ), 'url', __( 'Leave empty to link to the service page. Add a URL to send visitors somewhere else.', 'blush-salon' ) ),
		),
		'blush_testimonial' => array(
			'_blush_city' => array( __( 'City or short label', 'blush-salon' ), 'text', __( 'Shown under the name. The title is the client name, the main text is the quote, the featured image is the photo.', 'blush-salon' ) ),
		),
		'blush_team'        => array(
			'_blush_role'      => array( __( 'Role', 'blush-salon' ), 'text', __( 'For example: Founder, Hair Stylist. The title is the name, the featured image is the photo.', 'blush-salon' ) ),
			'_blush_facebook'  => array( __( 'Facebook URL', 'blush-salon' ), 'url', '' ),
			'_blush_twitter'   => array( __( 'X (Twitter) URL', 'blush-salon' ), 'url', '' ),
			'_blush_instagram' => array( __( 'Instagram URL', 'blush-salon' ), 'url', '' ),
			'_blush_linkedin'  => array( __( 'LinkedIn URL', 'blush-salon' ), 'url', '' ),
			'_blush_youtube'   => array( __( 'YouTube URL', 'blush-salon' ), 'url', '' ),
		),
		'blush_plan'        => array(
			'_blush_price'    => array( __( 'Price', 'blush-salon' ), 'text', __( 'With the currency sign, for example $49.', 'blush-salon' ) ),
			'_blush_period'   => array( __( 'Period', 'blush-salon' ), 'text', __( 'For example: Hourly, Per visit, Monthly.', 'blush-salon' ) ),
			'_blush_features' => array( __( 'Features', 'blush-salon' ), 'textarea', __( 'One feature per line.', 'blush-salon' ) ),
			'_blush_featured' => array( __( 'Highlight this plan', 'blush-salon' ), 'checkbox', __( 'The highlighted plan gets the pink header and a filled button.', 'blush-salon' ) ),
			'_blush_btn_text' => array( __( 'Button text', 'blush-salon' ), 'text', '' ),
			'_blush_btn_url'  => array( __( 'Button link', 'blush-salon' ), 'url', __( 'Leave empty to link to the booking form.', 'blush-salon' ) ),
			'_blush_note'     => array( __( 'Small note under the button', 'blush-salon' ), 'text', '' ),
		),
		'page'              => array(
			'_blush_hero_subtitle' => array( __( 'Hero subtitle', 'blush-salon' ), 'text', __( 'Short line under the page title. The page Featured Image is used as the hero background.', 'blush-salon' ) ),
		),
	);
	return isset( $fields[ $post_type ] ) ? $fields[ $post_type ] : array();
}

/**
 * Register meta boxes.
 */
function blush_add_meta_boxes() {
	$titles = array(
		'blush_service'     => __( 'Service details', 'blush-salon' ),
		'blush_testimonial' => __( 'Testimonial details', 'blush-salon' ),
		'blush_team'        => __( 'Team member details', 'blush-salon' ),
		'blush_plan'        => __( 'Plan details', 'blush-salon' ),
		'page'              => __( 'Page hero', 'blush-salon' ),
	);
	foreach ( $titles as $type => $title ) {
		add_meta_box( 'blush_details', $title, 'blush_render_meta_box', $type, 'page' === $type ? 'side' : 'normal', 'page' === $type ? 'default' : 'high' );
	}
	foreach ( array( 'blush_appointment', 'blush_message', 'blush_subscriber' ) as $type ) {
		add_meta_box( 'blush_entry', __( 'Details', 'blush-salon' ), 'blush_render_entry_box', $type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'blush_add_meta_boxes' );

/**
 * Render the editable meta box.
 *
 * @param WP_Post $post Post.
 */
function blush_render_meta_box( $post ) {
	wp_nonce_field( 'blush_save_meta_' . $post->ID, 'blush_meta_nonce' );
	foreach ( blush_meta_fields( $post->post_type ) as $key => $field ) {
		list( $label, $type, $help ) = $field;
		$value                       = get_post_meta( $post->ID, $key, true );
		$id                          = 'blush_field' . $key;
		echo '<p class="blush-field">';
		if ( 'checkbox' === $type ) {
			printf(
				'<label for="%1$s"><input type="checkbox" id="%1$s" name="blush_meta[%2$s]" value="1" %3$s> <strong>%4$s</strong></label>',
				esc_attr( $id ),
				esc_attr( $key ),
				checked( (bool) $value, true, false ),
				esc_html( $label )
			);
		} else {
			printf( '<label for="%s"><strong>%s</strong></label><br>', esc_attr( $id ), esc_html( $label ) );
			if ( 'textarea' === $type ) {
				printf( '<textarea id="%s" name="blush_meta[%s]" rows="6" class="widefat">%s</textarea>', esc_attr( $id ), esc_attr( $key ), esc_textarea( $value ) );
			} else {
				printf(
					'<input type="%s" id="%s" name="blush_meta[%s]" value="%s" class="widefat">',
					'url' === $type ? 'url' : 'text',
					esc_attr( $id ),
					esc_attr( $key ),
					esc_attr( $value )
				);
			}
		}
		if ( $help ) {
			printf( '<br><span class="description">%s</span>', esc_html( $help ) );
		}
		echo '</p>';
	}
}

/**
 * Save the editable meta box.
 *
 * @param int $post_id Post ID.
 */
function blush_save_meta( $post_id ) {
	if ( ! isset( $_POST['blush_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['blush_meta_nonce'] ) ), 'blush_save_meta_' . $post_id ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$fields = blush_meta_fields( get_post_type( $post_id ) );
	if ( ! $fields ) {
		return;
	}
	$posted = isset( $_POST['blush_meta'] ) && is_array( $_POST['blush_meta'] ) ? wp_unslash( $_POST['blush_meta'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per field below.

	foreach ( $fields as $key => $field ) {
		$type = $field[1];
		$raw  = isset( $posted[ $key ] ) && is_string( $posted[ $key ] ) ? $posted[ $key ] : '';
		if ( 'checkbox' === $type ) {
			$value = '' !== $raw ? '1' : '';
		} elseif ( 'url' === $type ) {
			$value = esc_url_raw( trim( $raw ) );
		} elseif ( 'textarea' === $type ) {
			$value = sanitize_textarea_field( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'blush_save_meta' );

/**
 * Read-only details box for form entries (appointments, messages, subscribers).
 *
 * @param WP_Post $post Post.
 */
function blush_render_entry_box( $post ) {
	$rows = array(
		'_blush_name'    => __( 'Name', 'blush-salon' ),
		'_blush_email'   => __( 'Email', 'blush-salon' ),
		'_blush_phone'   => __( 'Phone', 'blush-salon' ),
		'_blush_company' => __( 'Company', 'blush-salon' ),
		'_blush_subject' => __( 'Subject', 'blush-salon' ),
		'_blush_date'    => __( 'Appointment date', 'blush-salon' ),
		'_blush_time'    => __( 'Time', 'blush-salon' ),
		'_blush_service' => __( 'Service', 'blush-salon' ),
		'_blush_source'  => __( 'Sent from', 'blush-salon' ),
	);
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $rows as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		if ( '' === (string) $value ) {
			continue;
		}
		printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $label ), esc_html( $value ) );
	}
	if ( '' !== trim( $post->post_content ) ) {
		printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html__( 'Message', 'blush-salon' ), nl2br( esc_html( $post->post_content ) ) );
	}
	printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html__( 'Received', 'blush-salon' ), esc_html( get_the_date( '', $post ) . ' ' . get_the_time( '', $post ) ) );
	echo '</tbody></table>';
}
