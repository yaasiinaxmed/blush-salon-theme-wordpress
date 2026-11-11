<?php
/**
 * Elementor integration, full-width support, and shortcodes.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================================
   Page builder detection (Elementor, Beaver Builder, Divi, WPBakery,
   SiteOrigin, Brizy, Bricks, Oxygen and the block editor)
   ========================================================================== */

/**
 * Is any page builder's visual editor / live preview running right now?
 *
 * While a builder editor is open the template MUST call the_content(),
 * otherwise the builder cannot find its content area.
 *
 * @return bool
 */
function blush_is_builder_editing() {
	$editing = false;

	// Elementor.
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->preview ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		$editing = true;
	}

	// Query-string flags used by the builders' front-end editors / previews.
	$flags = array( 'elementor-preview', 'fl_builder', 'et_fb', 'vc_editable', 'vc_action', 'brizy-edit', 'brizy-edit-iframe', 'ct_builder', 'bricks', 'siteorigin_panels_live_editor' );
	foreach ( $flags as $flag ) {
		if ( isset( $_GET[ $flag ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$editing = true;
			break;
		}
	}

	if ( ! $editing && class_exists( 'FLBuilderModel' ) && method_exists( 'FLBuilderModel', 'is_builder_active' ) && FLBuilderModel::is_builder_active() ) {
		$editing = true;
	}

	return (bool) apply_filters( 'blush_is_builder_editing', $editing );
}

/**
 * Does the post contain content that was designed with a page builder?
 *
 * @param int|null $post_id Optional. Post ID.
 * @return bool
 */
function blush_has_builder_content( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	if ( ! $post_id ) {
		return false;
	}

	$has = false;

	// Elementor.
	$raw = get_post_meta( $post_id, '_elementor_data', true );
	if ( is_string( $raw ) ) {
		$trimmed = trim( $raw );
		if ( '' !== $trimmed && '[]' !== $trimmed && '""' !== $trimmed ) {
			$decoded = json_decode( $raw, true );
			$has     = is_array( $decoded ) && ! empty( $decoded );
		}
	} elseif ( is_array( $raw ) && ! empty( $raw ) ) {
		$has = true;
	}

	// Beaver Builder.
	if ( ! $has && get_post_meta( $post_id, '_fl_builder_enabled', true ) && get_post_meta( $post_id, '_fl_builder_data', true ) ) {
		$has = true;
	}

	// Divi (stores shortcodes in post_content).
	$content = (string) get_post_field( 'post_content', $post_id );
	if ( ! $has && 'on' === get_post_meta( $post_id, '_et_pb_use_builder', true ) && '' !== trim( $content ) ) {
		$has = true;
	}

	// WPBakery.
	if ( ! $has && ( false !== strpos( $content, '[vc_row' ) || false !== strpos( $content, '[vc_section' ) ) ) {
		$has = true;
	}

	// SiteOrigin Page Builder.
	if ( ! $has && get_post_meta( $post_id, 'panels_data', true ) ) {
		$has = true;
	}

	// Brizy.
	if ( ! $has && get_post_meta( $post_id, 'brizy_post_uid', true ) && get_post_meta( $post_id, 'brizy', true ) ) {
		$has = true;
	}

	// Bricks.
	if ( ! $has && get_post_meta( $post_id, '_bricks_page_content_2', true ) ) {
		$has = true;
	}

	return (bool) apply_filters( 'blush_has_builder_content', $has, $post_id );
}

/**
 * Is this page owned by a page builder (designed with one, or being edited in one)?
 *
 * @param int|null $post_id Optional. Post ID.
 * @return bool
 */
function blush_is_builder_page( $post_id = null ) {
	return blush_is_builder_editing() || blush_has_builder_content( $post_id );
}

/**
 * Backwards-compatible aliases (kept for child themes).
 */
function blush_is_elementor_page( $post_id = null ) {
	return blush_is_builder_page( $post_id );
}

function blush_has_elementor_content( $post_id = null ) {
	return blush_has_builder_content( $post_id );
}

/**
 * The static front page must honour a page template chosen in the editor.
 *
 * WordPress always prefers front-page.php over a selected page template, which
 * would stop "Elementor Full Width" (or any other template) from working on Home.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function blush_front_page_respect_template( $template ) {
	if ( is_front_page() && is_page() && basename( $template ) === 'front-page.php' ) {
		$slug = get_page_template_slug();
		if ( $slug && 'front-page.php' !== $slug ) {
			$located = locate_template( $slug );
			if ( $located ) {
				return $located;
			}
		}
	}
	return $template;
}
add_filter( 'template_include', 'blush_front_page_respect_template' );

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
	if ( is_singular() && blush_is_builder_page() ) {
		$classes[] = 'blush-elementor-active';
		$classes[] = 'blush-builder-active';
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
