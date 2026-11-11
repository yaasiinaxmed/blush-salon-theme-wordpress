<?php
/**
 * Customizer settings (Appearance > Customize).
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a map value: a URL or pasted iframe code. Only the URL is kept.
 *
 * @param string $value Raw value.
 * @return string
 */
function blush_sanitize_map( $value ) {
	$value = trim( (string) $value );
	if ( preg_match( '/src=["\']([^"\']+)["\']/i', $value, $m ) ) {
		$value = html_entity_decode( $m[1], ENT_QUOTES );
	}
	return esc_url_raw( $value, array( 'https' ) );
}

/**
 * Sanitize an email (empty allowed).
 *
 * @param string $value Raw value.
 * @return string
 */
function blush_sanitize_email( $value ) {
	$value = sanitize_email( $value );
	return is_email( $value ) ? $value : '';
}

/**
 * Pick the sanitize callback for a field.
 *
 * @param string $key  Field key.
 * @param string $type Field type.
 * @return callable
 */
function blush_sanitizer( $key, $type ) {
	if ( 'map_url' === $key ) {
		return 'blush_sanitize_map';
	}
	switch ( $type ) {
		case 'color':
			return 'sanitize_hex_color';
		case 'url':
		case 'image':
			return 'esc_url_raw';
		case 'email':
			return 'blush_sanitize_email';
		case 'textarea':
			return 'sanitize_textarea_field';
		default:
			return 'sanitize_text_field';
	}
}

/**
 * Register panel, sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function blush_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'blush_panel',
		array(
			'title'       => __( 'Blush Salon Options', 'blush-salon' ),
			'description' => __( 'Texts, images and contact details used by the theme.', 'blush-salon' ),
			'priority'    => 30,
		)
	);

	$priority = 10;
	foreach ( blush_sections() as $id => $title ) {
		$wp_customize->add_section(
			$id,
			array(
				'title'    => $title,
				'panel'    => 'blush_panel',
				'priority' => $priority,
			)
		);
		$priority += 5;
	}

	foreach ( blush_fields() as $key => $field ) {
		list( $section, $type, $label, $default ) = $field;

		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $default,
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => blush_sanitizer( $key, $type ),
			)
		);

		$args = array(
			'label'    => $label,
			'section'  => $section,
			'settings' => $key,
		);

		if ( 'color' === $type ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, $args ) );
		} elseif ( 'image' === $type ) {
			$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $key, $args ) );
		} else {
			$args['type'] = in_array( $type, array( 'textarea', 'url', 'email' ), true ) ? $type : 'text';
			$wp_customize->add_control( $key, $args );
		}
	}
}
add_action( 'customize_register', 'blush_customize_register' );
