<?php
/**
 * Central list of theme options (Customizer fields) and their default values.
 *
 * Each field: key => array( section, type, label, default ).
 * Types: text, textarea, url, email, number, image, color.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer sections.
 *
 * @return array
 */
function blush_sections() {
	return array(
		'blush_hero'         => __( 'Home: Hero', 'blush-salon' ),
		'blush_about'        => __( 'Section: Who We Are', 'blush-salon' ),
		'blush_cta'          => __( 'Section: CTA Banner', 'blush-salon' ),
		'blush_services'     => __( 'Section: Services', 'blush-salon' ),
		'blush_booking'      => __( 'Section: Book an Appointment', 'blush-salon' ),
		'blush_why'          => __( 'Section: Why Choose Us', 'blush-salon' ),
		'blush_testimonials' => __( 'Section: Testimonials', 'blush-salon' ),
		'blush_logos'        => __( 'Section: Partner Logos', 'blush-salon' ),
		'blush_video'        => __( 'Section: Video Banner', 'blush-salon' ),
		'blush_values'       => __( 'About Page: Vision, Mission, Motto', 'blush-salon' ),
		'blush_team'         => __( 'About Page: Team', 'blush-salon' ),
		'blush_pricing'      => __( 'Pricing Page: Plans and FAQ', 'blush-salon' ),
		'blush_contact_page' => __( 'Contact Page: Texts and Map', 'blush-salon' ),
		'blush_contact'      => __( 'Contact Details', 'blush-salon' ),
		'blush_social'       => __( 'Social Links', 'blush-salon' ),
		'blush_page_hero'    => __( 'Inner Page Hero', 'blush-salon' ),
		'blush_footer'       => __( 'Footer', 'blush-salon' ),
	);
}

/**
 * All theme option fields.
 *
 * @return array
 */
function blush_fields() {
	$f = array();

	// Colors (shown in the core "Colors" section).
	$f['color_primary'] = array( 'colors', 'color', __( 'Primary color (pink)', 'blush-salon' ), '#EA5084' );
	$f['color_accent']  = array( 'colors', 'color', __( 'Accent color (salmon)', 'blush-salon' ), '#FDADA8' );
	$f['color_blush']   = array( 'colors', 'color', __( 'Blush background color', 'blush-salon' ), '#FEDBD9' );

	// Hero.
	$f['hero_title']    = array( 'blush_hero', 'text', __( 'Headline', 'blush-salon' ), __( 'Beautiful Care for Your Hair and Body', 'blush-salon' ) );
	$f['hero_text']     = array( 'blush_hero', 'textarea', __( 'Subtext', 'blush-salon' ), __( 'We are a friendly team of beauty lovers, and we are ready to look after you.', 'blush-salon' ) );
	$f['hero_btn_text'] = array( 'blush_hero', 'text', __( 'Button text', 'blush-salon' ), __( 'Discover More', 'blush-salon' ) );
	$f['hero_btn_url']  = array( 'blush_hero', 'url', __( 'Button link (empty = Services page)', 'blush-salon' ), '' );
	$f['hero_image']    = array( 'blush_hero', 'image', __( 'Hero image', 'blush-salon' ), '' );

	// Who we are.
	$f['about_eyebrow']  = array( 'blush_about', 'text', __( 'Small label', 'blush-salon' ), __( 'Who we are', 'blush-salon' ) );
	$f['about_title']    = array( 'blush_about', 'text', __( 'Heading', 'blush-salon' ), __( 'A calm place to look and feel your best.', 'blush-salon' ) );
	$f['about_text']     = array( 'blush_about', 'textarea', __( 'Text', 'blush-salon' ), __( 'This is placeholder text. Write a short introduction about your salon here: who you are, what you do and why clients love to visit.', 'blush-salon' ) );
	$f['years_number']   = array( 'blush_about', 'text', __( 'Years of experience number', 'blush-salon' ), '15+' );
	$f['years_label']    = array( 'blush_about', 'text', __( 'Years of experience label', 'blush-salon' ), __( 'Years of Experience', 'blush-salon' ) );
	for ( $i = 1; $i <= 4; $i++ ) {
		/* translators: %d: image number. */
		$f[ 'about_img_' . $i ] = array( 'blush_about', 'image', sprintf( __( 'Collage image %d', 'blush-salon' ), $i ), '' );
	}
	$f['about_f1_title'] = array( 'blush_about', 'text', __( 'Feature 1 title', 'blush-salon' ), __( 'Beauty Shop', 'blush-salon' ) );
	$f['about_f1_text']  = array( 'blush_about', 'textarea', __( 'Feature 1 text', 'blush-salon' ), __( 'Placeholder text. Tell visitors about the products you sell in your salon.', 'blush-salon' ) );
	$f['about_f2_title'] = array( 'blush_about', 'text', __( 'Feature 2 title', 'blush-salon' ), __( 'Makeup Artist', 'blush-salon' ) );
	$f['about_f2_text']  = array( 'blush_about', 'textarea', __( 'Feature 2 text', 'blush-salon' ), __( 'Placeholder text. Tell visitors about your makeup service for special days.', 'blush-salon' ) );

	// CTA banner.
	$f['cta_title']    = array( 'blush_cta', 'text', __( 'Heading', 'blush-salon' ), __( 'It is Time to Shine! Try Our Makeup Now!', 'blush-salon' ) );
	$f['cta_text']     = array( 'blush_cta', 'textarea', __( 'Text', 'blush-salon' ), __( 'Placeholder text. Add one or two sentences that invite visitors to try your most popular service.', 'blush-salon' ) );
	$f['cta_btn_text'] = array( 'blush_cta', 'text', __( 'Button text', 'blush-salon' ), __( 'Discover More', 'blush-salon' ) );
	$f['cta_btn_url']  = array( 'blush_cta', 'url', __( 'Button link (empty = Services page)', 'blush-salon' ), '' );
	$f['cta_image']    = array( 'blush_cta', 'image', __( 'Banner image', 'blush-salon' ), '' );

	// Services.
	$f['services_eyebrow']  = array( 'blush_services', 'text', __( 'Small label', 'blush-salon' ), __( 'The Beauty Secret Revealed', 'blush-salon' ) );
	$f['services_title']    = array( 'blush_services', 'text', __( 'Heading', 'blush-salon' ), __( 'Exclusive Services at Our Salon.', 'blush-salon' ) );
	$f['services_text']     = array( 'blush_services', 'textarea', __( 'Text', 'blush-salon' ), __( 'Placeholder text. Describe your services in a few words. You can add, edit and remove services in the WordPress admin under Services.', 'blush-salon' ) );
	$f['services_btn_text'] = array( 'blush_services', 'text', __( 'Button text', 'blush-salon' ), __( 'Discover more', 'blush-salon' ) );
	$f['services_btn_url']  = array( 'blush_services', 'url', __( 'Button link (empty = Services page)', 'blush-salon' ), '' );

	// Booking.
	$f['booking_eyebrow']    = array( 'blush_booking', 'text', __( 'Small label', 'blush-salon' ), __( 'Book Your Seat Today!', 'blush-salon' ) );
	$f['booking_title']      = array( 'blush_booking', 'text', __( 'Heading', 'blush-salon' ), __( 'Transforming Beauty, One Smile at a Time', 'blush-salon' ) );
	$f['booking_text']       = array( 'blush_booking', 'textarea', __( 'Text', 'blush-salon' ), __( 'Placeholder text. Explain how booking works and how fast you will confirm the appointment.', 'blush-salon' ) );
	$f['booking_benefits']   = array( 'blush_booking', 'textarea', __( 'Benefits (one per line)', 'blush-salon' ), __( "Friendly, trained staff\nClean and safe tools\nQuality products\nFlexible opening hours\nFair prices\nRelaxing atmosphere", 'blush-salon' ) );
	$f['booking_form_title'] = array( 'blush_booking', 'text', __( 'Form title', 'blush-salon' ), __( 'Book an Appointment', 'blush-salon' ) );

	// Why choose us.
	$f['why_eyebrow'] = array( 'blush_why', 'text', __( 'Small label', 'blush-salon' ), __( 'Why Choose Us', 'blush-salon' ) );
	$f['why_title']   = array( 'blush_why', 'text', __( 'Heading', 'blush-salon' ), __( 'Where Beauty Meets Confidence', 'blush-salon' ) );
	$f['why_text']    = array( 'blush_why', 'textarea', __( 'Text', 'blush-salon' ), __( 'Placeholder text. Say in one or two sentences why clients should choose your salon.', 'blush-salon' ) );
	$f['why_image']   = array( 'blush_why', 'image', __( 'Background image', 'blush-salon' ), '' );
	$why              = array(
		1 => __( 'Hygiene and Safety', 'blush-salon' ),
		2 => __( 'Professional Services', 'blush-salon' ),
		3 => __( 'Stylish Makeovers', 'blush-salon' ),
		4 => __( 'The Best Quality', 'blush-salon' ),
	);
	foreach ( $why as $i => $title ) {
		/* translators: %d: feature number. */
		$f[ 'why_f' . $i . '_title' ] = array( 'blush_why', 'text', sprintf( __( 'Feature %d title', 'blush-salon' ), $i ), $title );
		/* translators: %d: feature number. */
		$f[ 'why_f' . $i . '_text' ] = array( 'blush_why', 'textarea', sprintf( __( 'Feature %d text', 'blush-salon' ), $i ), __( 'Short placeholder text for this feature.', 'blush-salon' ) );
	}

	// Testimonials.
	$f['testi_eyebrow'] = array( 'blush_testimonials', 'text', __( 'Small label', 'blush-salon' ), __( 'Testimonials', 'blush-salon' ) );
	$f['testi_title']   = array( 'blush_testimonials', 'text', __( 'Heading', 'blush-salon' ), __( 'Customers Feedback & Reviews', 'blush-salon' ) );

	// Logos.
	$f['logos_title'] = array( 'blush_logos', 'text', __( 'Heading', 'blush-salon' ), __( 'Where Beauty Meets Empowerment.', 'blush-salon' ) );
	$f['logos_text']  = array( 'blush_logos', 'textarea', __( 'Text', 'blush-salon' ), __( 'Placeholder text. Show the brands and partners you work with.', 'blush-salon' ) );
	for ( $i = 1; $i <= 8; $i++ ) {
		/* translators: %d: logo number. */
		$f[ 'logo_' . $i ] = array( 'blush_logos', 'image', sprintf( __( 'Logo %d', 'blush-salon' ), $i ), '' );
	}

	// Video banner.
	$f['video_title']    = array( 'blush_video', 'text', __( 'Heading', 'blush-salon' ), __( 'Your Path to Beauty and Relaxation', 'blush-salon' ) );
	$f['video_text']     = array( 'blush_video', 'textarea', __( 'Text', 'blush-salon' ), __( 'Placeholder text. Invite visitors to watch a short video about your salon.', 'blush-salon' ) );
	$f['video_btn_text'] = array( 'blush_video', 'text', __( 'Button text', 'blush-salon' ), __( 'Discover more', 'blush-salon' ) );
	$f['video_btn_url']  = array( 'blush_video', 'url', __( 'Button link (empty = About page)', 'blush-salon' ), '' );
	$f['video_url']      = array( 'blush_video', 'url', __( 'Video URL (YouTube, Vimeo or .mp4). Empty = no play button.', 'blush-salon' ), '' );
	$f['video_image']    = array( 'blush_video', 'image', __( 'Background image', 'blush-salon' ), '' );

	// Values.
	$f['values_eyebrow'] = array( 'blush_values', 'text', __( 'Small label', 'blush-salon' ), __( 'Our Value', 'blush-salon' ) );
	$f['values_title']   = array( 'blush_values', 'text', __( 'Heading', 'blush-salon' ), __( 'Where Beauty Blossoms, Confidence Shines.', 'blush-salon' ) );
	$values              = array(
		1 => __( 'Our Vision', 'blush-salon' ),
		2 => __( 'Our Mission', 'blush-salon' ),
		3 => __( 'Our Motto', 'blush-salon' ),
	);
	foreach ( $values as $i => $title ) {
		/* translators: %d: card number. */
		$f[ 'value_' . $i . '_title' ] = array( 'blush_values', 'text', sprintf( __( 'Card %d title', 'blush-salon' ), $i ), $title );
		/* translators: %d: card number. */
		$f[ 'value_' . $i . '_text' ] = array( 'blush_values', 'textarea', sprintf( __( 'Card %d text', 'blush-salon' ), $i ), __( 'Placeholder text. Write two short sentences about this value of your salon.', 'blush-salon' ) );
	}

	// Team.
	$f['team_eyebrow'] = array( 'blush_team', 'text', __( 'Small label', 'blush-salon' ), __( 'Meet Our Team', 'blush-salon' ) );
	$f['team_title']   = array( 'blush_team', 'text', __( 'Heading', 'blush-salon' ), __( 'Teamwork makes the dream work.', 'blush-salon' ) );

	// Pricing + FAQ.
	$f['pricing_eyebrow'] = array( 'blush_pricing', 'text', __( 'Pricing small label', 'blush-salon' ), __( 'Choose Your Package', 'blush-salon' ) );
	$f['pricing_title']   = array( 'blush_pricing', 'text', __( 'Pricing heading', 'blush-salon' ), __( 'We are here to help you with all your beauty needs', 'blush-salon' ) );
	$f['faq_eyebrow']     = array( 'blush_pricing', 'text', __( 'FAQ small label', 'blush-salon' ), __( 'Common Questions', 'blush-salon' ) );
	$f['faq_title']       = array( 'blush_pricing', 'text', __( 'FAQ heading', 'blush-salon' ), __( 'Most Popular Questions', 'blush-salon' ) );

	// Contact page.
	$f['contact_title']      = array( 'blush_contact_page', 'text', __( 'Heading', 'blush-salon' ), __( 'Whenever you need us, we are here for you.', 'blush-salon' ) );
	$f['contact_text']       = array( 'blush_contact_page', 'textarea', __( 'Text', 'blush-salon' ), __( 'Placeholder text. Tell visitors the best way to reach you and when you are open.', 'blush-salon' ) );
	$f['contact_form_title'] = array( 'blush_contact_page', 'text', __( 'Form title', 'blush-salon' ), __( 'Send us a message', 'blush-salon' ) );
	$f['contact_form_text']  = array( 'blush_contact_page', 'textarea', __( 'Form text', 'blush-salon' ), __( 'Fill in the form and we will reply as soon as we can.', 'blush-salon' ) );
	$f['map_url']            = array( 'blush_contact_page', 'textarea', __( 'Google Maps embed URL (Google Maps > Share > Embed a map; paste the link or the whole iframe code)', 'blush-salon' ), '' );

	// Contact details.
	$f['phone']        = array( 'blush_contact', 'text', __( 'Phone number', 'blush-salon' ), '(555) 010-2030' );
	$f['phone_2']      = array( 'blush_contact', 'text', __( 'Second phone number (optional)', 'blush-salon' ), '' );
	$f['email']        = array( 'blush_contact', 'email', __( 'Email address', 'blush-salon' ), 'hello@example.com' );
	$f['address']      = array( 'blush_contact', 'textarea', __( 'Address', 'blush-salon' ), __( "123 Example Street\nYour City, Your Country", 'blush-salon' ) );
	$f['notify_email'] = array( 'blush_contact', 'email', __( 'Send form emails to (empty = site admin email)', 'blush-salon' ), '' );

	// Social.
	$f['social_facebook']  = array( 'blush_social', 'url', __( 'Facebook URL', 'blush-salon' ), '' );
	$f['social_twitter']   = array( 'blush_social', 'url', __( 'X (Twitter) URL', 'blush-salon' ), '' );
	$f['social_instagram'] = array( 'blush_social', 'url', __( 'Instagram URL', 'blush-salon' ), '' );
	$f['social_linkedin']  = array( 'blush_social', 'url', __( 'LinkedIn URL', 'blush-salon' ), '' );
	$f['social_youtube']   = array( 'blush_social', 'url', __( 'YouTube URL', 'blush-salon' ), '' );
	$f['social_tiktok']    = array( 'blush_social', 'url', __( 'TikTok URL', 'blush-salon' ), '' );

	// Page hero.
	$f['page_hero_image']    = array( 'blush_page_hero', 'image', __( 'Default background image (a page Featured Image replaces it for that page)', 'blush-salon' ), '' );
	$f['page_hero_subtitle'] = array( 'blush_page_hero', 'text', __( 'Default subtitle', 'blush-salon' ), __( 'We do everything you need to look your best.', 'blush-salon' ) );

	// Footer.
	$f['footer_blurb']     = array( 'blush_footer', 'textarea', __( 'Short text under the logo', 'blush-salon' ), __( 'Placeholder text. Add a short sentence about your salon for the footer.', 'blush-salon' ) );
	$f['newsletter_title'] = array( 'blush_footer', 'text', __( 'Newsletter title', 'blush-salon' ), __( 'Newsletter', 'blush-salon' ) );
	$f['newsletter_text']  = array( 'blush_footer', 'textarea', __( 'Newsletter text', 'blush-salon' ), __( 'Sign up to our newsletter to get news, tips and special offers.', 'blush-salon' ) );
	$f['copyright']        = array( 'blush_footer', 'text', __( 'Copyright text ({year} = current year, {site} = site name)', 'blush-salon' ), __( 'Copyright © {year} {site}. All rights reserved.', 'blush-salon' ) );

	return $f;
}

/**
 * Get a theme option with its default.
 *
 * @param string $key Field key.
 * @return string
 */
function blush_mod( $key ) {
	static $fields = null;
	if ( null === $fields ) {
		$fields = blush_fields();
	}
	$default = isset( $fields[ $key ] ) ? $fields[ $key ][3] : '';
	$value   = get_theme_mod( $key, $default );
	return is_string( $value ) ? $value : $default;
}

/**
 * Bundled placeholder image URL.
 *
 * @param string $file File name inside assets/images.
 * @return string
 */
function blush_asset_img( $file ) {
	return BLUSH_URI . '/assets/images/' . ltrim( $file, '/' );
}

/**
 * Image theme option with a bundled fallback file.
 *
 * @param string $key      Field key.
 * @param string $fallback File name inside assets/images.
 * @return string
 */
function blush_img( $key, $fallback ) {
	$value = blush_mod( $key );
	return '' !== $value ? $value : blush_asset_img( $fallback );
}
