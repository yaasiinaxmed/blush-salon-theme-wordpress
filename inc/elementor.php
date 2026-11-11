<?php
/**
 * Elementor integration, full-width support, and shortcodes.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if the given post (or current queried post) was built with or is being edited in Elementor.
 *
 * @param int|null $post_id Optional. Post ID.
 * @return bool
 */
function blush_is_elementor_page( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	if ( ! $post_id ) {
		return false;
	}

	// 1. Elementor editor / preview mode
	if ( class_exists( '\Elementor\Plugin' ) ) {
		if ( isset( \Elementor\Plugin::$instance->preview ) && \Elementor\Plugin::$instance->preview->is_preview_mode( $post_id ) ) {
			return true;
		}
		if ( isset( \Elementor\Plugin::$instance->db ) && method_exists( \Elementor\Plugin::$instance->db, 'is_built_with_elementor' ) ) {
			if ( \Elementor\Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
				return true;
			}
		}
	}

	// 2. Post meta check
	if ( 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true ) ) {
		return true;
	}

	// 3. Query string check (when entering Elementor editor / preview)
	if ( isset( $_GET['elementor-preview'] ) || ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) ) {
		return true;
	}

	return false;
}

/**
 * Check if the post has actual Elementor widgets/content saved.
 *
 * @param int|null $post_id Optional. Post ID.
 * @return bool
 */
function blush_has_elementor_content( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	if ( ! $post_id ) {
		return false;
	}

	$raw_data = get_post_meta( $post_id, '_elementor_data', true );
	if ( empty( $raw_data ) ) {
		return false;
	}

	if ( is_string( $raw_data ) ) {
		$trimmed = trim( $raw_data );
		if ( '[]' === $trimmed || '""' === $trimmed || '' === $trimmed ) {
			return false;
		}
		$data = json_decode( $raw_data, true );
		return is_array( $data ) && ! empty( $data );
	}

	return is_array( $raw_data ) && ! empty( $raw_data );
}


/**
 * Register Elementor Pro locations (Header, Footer, Single, Archive).
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager Theme manager instance.
 */
function blush_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'blush_register_elementor_locations' );

/**
 * Add custom body classes for Elementor pages.
 *
 * @param array $classes Body classes.
 * @return array
 */
function blush_elementor_body_classes( $classes ) {
	if ( blush_is_elementor_page() ) {
		$classes[] = 'blush-elementor-active';
		$classes[] = 'blush-full-width';
	}
	return $classes;
}
add_filter( 'body_class', 'blush_elementor_body_classes' );

/* ==========================================================================
   Elementor Shortcodes - easily drop any theme section into Elementor
   ========================================================================== */

/**
 * Helper to buffer and return template parts.
 *
 * @param string $slug Template slug.
 * @param string|null $name Template name.
 * @param array $args Template arguments.
 * @return string
 */
function blush_get_template_part_output( $slug, $name = null, $args = array() ) {
	ob_start();
	get_template_part( $slug, $name, $args );
	return ob_get_clean();
}

/**
 * Shortcode: [blush_booking_form]
 */
function blush_shortcode_booking_form( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-appointment' );
}
add_shortcode( 'blush_booking_form', 'blush_shortcode_booking_form' );
add_shortcode( 'blush_appointment', 'blush_shortcode_booking_form' );

/**
 * Shortcode: [blush_booking_bar]
 */
function blush_shortcode_booking_bar( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/booking-bar' );
}
add_shortcode( 'blush_booking_bar', 'blush_shortcode_booking_bar' );

/**
 * Shortcode: [blush_home_hero]
 */
function blush_shortcode_home_hero( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/home-hero' );
}
add_shortcode( 'blush_home_hero', 'blush_shortcode_home_hero' );

/**
 * Shortcode: [blush_services limit="6" show_button="true"]
 */
function blush_shortcode_services( $atts = array() ) {
	$atts = shortcode_atts(
		array(
			'limit'       => -1,
			'show_button' => true,
		),
		$atts,
		'blush_services'
	);

	$limit       = (int) $atts['limit'];
	$show_button = filter_var( $atts['show_button'], FILTER_VALIDATE_BOOLEAN );

	return blush_get_template_part_output(
		'template-parts/section-services',
		null,
		array(
			'limit'       => $limit,
			'show_button' => $show_button,
		)
	);
}
add_shortcode( 'blush_services', 'blush_shortcode_services' );

/**
 * Shortcode: [blush_pricing]
 */
function blush_shortcode_pricing( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-pricing' );
}
add_shortcode( 'blush_pricing', 'blush_shortcode_pricing' );

/**
 * Shortcode: [blush_testimonials]
 */
function blush_shortcode_testimonials( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-testimonials' );
}
add_shortcode( 'blush_testimonials', 'blush_shortcode_testimonials' );

/**
 * Shortcode: [blush_team]
 */
function blush_shortcode_team( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-team' );
}
add_shortcode( 'blush_team', 'blush_shortcode_team' );

/**
 * Shortcode: [blush_faqs]
 */
function blush_shortcode_faqs( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-faq' );
}
add_shortcode( 'blush_faqs', 'blush_shortcode_faqs' );
add_shortcode( 'blush_faq', 'blush_shortcode_faqs' );

/**
 * Shortcode: [blush_contact_form]
 */
function blush_shortcode_contact_form( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-contact' );
}
add_shortcode( 'blush_contact_form', 'blush_shortcode_contact_form' );

/**
 * Shortcode: [blush_about]
 */
function blush_shortcode_about( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-about' );
}
add_shortcode( 'blush_about', 'blush_shortcode_about' );

/**
 * Shortcode: [blush_values]
 */
function blush_shortcode_values( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-values' );
}
add_shortcode( 'blush_values', 'blush_shortcode_values' );

/**
 * Shortcode: [blush_why]
 */
function blush_shortcode_why( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-why' );
}
add_shortcode( 'blush_why', 'blush_shortcode_why' );

/**
 * Shortcode: [blush_cta]
 */
function blush_shortcode_cta( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-cta' );
}
add_shortcode( 'blush_cta', 'blush_shortcode_cta' );

/**
 * Shortcode: [blush_logos]
 */
function blush_shortcode_logos( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-logos' );
}
add_shortcode( 'blush_logos', 'blush_shortcode_logos' );

/**
 * Shortcode: [blush_video]
 */
function blush_shortcode_video( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-video' );
}
add_shortcode( 'blush_video', 'blush_shortcode_video' );

/**
 * Shortcode: [blush_map]
 */
function blush_shortcode_map( $atts = array() ) {
	return blush_get_template_part_output( 'template-parts/section-map' );
}
add_shortcode( 'blush_map', 'blush_shortcode_map' );
