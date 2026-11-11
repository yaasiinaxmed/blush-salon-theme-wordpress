<?php
/**
 * Helper functions used by the templates.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find the URL of the first published page that uses a page template.
 *
 * @param string $template Template file, relative to the theme (for example page-templates/template-about.php).
 * @return string URL or empty string.
 */
function blush_page_url( $template ) {
	static $cache = array();
	if ( isset( $cache[ $template ] ) ) {
		return $cache[ $template ];
	}
	$url = '';
	if ( 'front-page.php' === $template ) {
		$url = home_url( '/' );
	} else {
		$ids = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'orderby'        => 'ID',
				'order'          => 'ASC',
			)
		);
		if ( $ids ) {
			$url = get_permalink( $ids[0] );
		}
	}
	$cache[ $template ] = $url;
	return $url;
}

/**
 * Button link from the Customizer, or a page that uses the given template, or an anchor.
 *
 * @param string $key      Theme option key.
 * @param string $template Page template slug: about, services, pricing, contact.
 * @param string $anchor   Fallback anchor on the home page.
 * @return string
 */
function blush_link( $key, $template = '', $anchor = '' ) {
	$url = $key ? blush_mod( $key ) : '';
	if ( '' !== $url ) {
		return $url;
	}
	if ( $template ) {
		$url = blush_page_url( 'page-templates/template-' . $template . '.php' );
		if ( $url ) {
			return $url . $anchor;
		}
	}
	return home_url( '/' ) . ( $anchor ? $anchor : '' );
}

/**
 * Turn a phone number into a tel: link target.
 *
 * @param string $phone Phone number.
 * @return string
 */
function blush_tel( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Site logo: custom logo image, or a text logo.
 */
function blush_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="text-logo" href="%1$s" rel="home"><span class="text-logo__mark" aria-hidden="true"></span><span class="text-logo__name">%2$s</span></a>',
		esc_url( home_url( '/' ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Social links that have a URL.
 *
 * @return array name => array( url, label ).
 */
function blush_socials() {
	$labels = array(
		'facebook'  => __( 'Facebook', 'blush-salon' ),
		'twitter'   => __( 'X (Twitter)', 'blush-salon' ),
		'instagram' => __( 'Instagram', 'blush-salon' ),
		'linkedin'  => __( 'LinkedIn', 'blush-salon' ),
		'youtube'   => __( 'YouTube', 'blush-salon' ),
		'tiktok'    => __( 'TikTok', 'blush-salon' ),
	);
	$out    = array();
	foreach ( $labels as $name => $label ) {
		$url = blush_mod( 'social_' . $name );
		if ( '' !== $url ) {
			$out[ $name ] = array( $url, $label );
		}
	}
	return $out;
}

/**
 * Print a list of social icon links.
 *
 * @param array  $links Optional name => url list. Default: theme options.
 * @param string $class Extra class.
 */
function blush_social_list( $links = null, $class = '' ) {
	if ( null === $links ) {
		$links = blush_socials();
	} else {
		$all   = array(
			'facebook'  => __( 'Facebook', 'blush-salon' ),
			'twitter'   => __( 'X (Twitter)', 'blush-salon' ),
			'instagram' => __( 'Instagram', 'blush-salon' ),
			'linkedin'  => __( 'LinkedIn', 'blush-salon' ),
			'youtube'   => __( 'YouTube', 'blush-salon' ),
			'tiktok'    => __( 'TikTok', 'blush-salon' ),
		);
		$clean = array();
		foreach ( $links as $name => $url ) {
			if ( $url && isset( $all[ $name ] ) ) {
				$clean[ $name ] = array( $url, $all[ $name ] );
			}
		}
		$links = $clean;
	}
	if ( ! $links ) {
		return;
	}
	echo '<ul class="social-list ' . esc_attr( $class ) . '">';
	foreach ( $links as $name => $data ) {
		printf(
			'<li><a href="%1$s" target="_blank" rel="noopener noreferrer"><span class="screen-reader-text">%2$s</span>%3$s</a></li>',
			esc_url( $data[0] ),
			esc_html( $data[1] ),
			blush_get_icon( $name ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
	echo '</ul>';
}

/**
 * Run a query for one of the theme post types.
 *
 * @param string $post_type Post type.
 * @param int    $limit     Max items, -1 for all.
 * @return WP_Post[]
 */
function blush_query( $post_type, $limit = -1 ) {
	return get_posts(
		array(
			'post_type'        => $post_type,
			'post_status'      => 'publish',
			'posts_per_page'   => $limit,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'suppress_filters' => false,
		)
	);
}

/**
 * Thumbnail URL with a bundled fallback.
 *
 * @param int    $post_id  Post ID.
 * @param string $size     Image size.
 * @param string $fallback File in assets/images.
 * @return string
 */
function blush_thumb( $post_id, $size, $fallback ) {
	$url = get_the_post_thumbnail_url( $post_id, $size );
	return $url ? $url : blush_asset_img( $fallback );
}

/**
 * Placeholder (sample) content, used when a post type has no entries yet.
 *
 * @param string $type services|testimonials|team|plans|faqs.
 * @return array
 */
function blush_samples( $type ) {
	switch ( $type ) {
		case 'services':
			$titles = array(
				__( 'Hair Coloring', 'blush-salon' ),
				__( 'Hair Straightening', 'blush-salon' ),
				__( 'Haircut & Treatment', 'blush-salon' ),
				__( 'Massage & Body Treatment', 'blush-salon' ),
				__( 'Body Scrub', 'blush-salon' ),
				__( 'Aromatherapy Massage', 'blush-salon' ),
			);
			$out    = array();
			foreach ( $titles as $i => $title ) {
				$out[] = array(
					'title'   => $title,
					'excerpt' => __( 'Sample service. Add a short description of this service in the admin.', 'blush-salon' ),
					'content' => __( 'This is a sample service. Replace this text with a full description: what is included, how long it takes and who it is for.', 'blush-salon' ),
					'image'   => 'service-' . ( $i + 1 ) . '.svg',
				);
			}
			return $out;

		case 'testimonials':
			$people = array(
				array( __( 'Amina Yusuf', 'blush-salon' ), __( 'Regular client', 'blush-salon' ) ),
				array( __( 'Sara Lind', 'blush-salon' ), __( 'Bride', 'blush-salon' ) ),
				array( __( 'Maya Chen', 'blush-salon' ), __( 'First visit', 'blush-salon' ) ),
				array( __( 'Leyla Omar', 'blush-salon' ), __( 'Regular client', 'blush-salon' ) ),
			);
			$out    = array();
			foreach ( $people as $p ) {
				$out[] = array(
					'name'  => $p[0],
					'city'  => $p[1],
					'quote' => __( 'This is a sample review. Replace it with real words from a happy client. Two or three short sentences work best.', 'blush-salon' ),
				);
			}
			return $out;

		case 'team':
			$people = array(
				array( __( 'Emily Stone', 'blush-salon' ), __( 'Founder', 'blush-salon' ) ),
				array( __( 'Hana Ali', 'blush-salon' ), __( 'Hair Stylist', 'blush-salon' ) ),
				array( __( 'Grace Obi', 'blush-salon' ), __( 'Makeup Artist', 'blush-salon' ) ),
				array( __( 'Nora Berg', 'blush-salon' ), __( 'Massage Therapist', 'blush-salon' ) ),
			);
			$out    = array();
			foreach ( $people as $i => $p ) {
				$out[] = array(
					'name'  => $p[0],
					'role'  => $p[1],
					'image' => 'team-' . ( $i + 1 ) . '.svg',
				);
			}
			return $out;

		case 'plans':
			$plans = array(
				array( __( 'Basic Package', 'blush-salon' ), '$49', false ),
				array( __( 'Premium Package', 'blush-salon' ), '$59', true ),
				array( __( 'Professional Package', 'blush-salon' ), '$79', false ),
			);
			$out   = array();
			foreach ( $plans as $p ) {
				$out[] = array(
					'title'    => $p[0],
					'excerpt'  => __( 'Sample plan. Add a short description of what this package is for.', 'blush-salon' ),
					'price'    => $p[1],
					'period'   => __( 'Hourly', 'blush-salon' ),
					'features' => __( "Sample feature one\nSample feature two\nSample feature three\nSample feature four\nSample feature five", 'blush-salon' ),
					'featured' => $p[2],
					'btn_text' => __( 'Get Started', 'blush-salon' ),
					'btn_url'  => '',
					'note'     => __( '*Sample note. Prices and details are examples only.', 'blush-salon' ),
				);
			}
			return $out;

		case 'faqs':
			$questions = array(
				__( 'How do I book an appointment?', 'blush-salon' ),
				__( 'Can I change or cancel my booking?', 'blush-salon' ),
				__( 'Which products do you use?', 'blush-salon' ),
				__( 'Do you offer gift cards?', 'blush-salon' ),
				__( 'How early should I arrive?', 'blush-salon' ),
				__( 'Do you do bridal makeup?', 'blush-salon' ),
				__( 'What are your opening hours?', 'blush-salon' ),
				__( 'Which payment methods do you accept?', 'blush-salon' ),
			);
			$out       = array();
			foreach ( $questions as $q ) {
				$out[] = array(
					'question' => $q,
					'answer'   => __( 'This is a sample answer. Replace it with your own answer in the admin under FAQs.', 'blush-salon' ),
				);
			}
			return $out;
	}
	return array();
}

/**
 * Services for display.
 *
 * @param int $limit Max items.
 * @return array[] title, excerpt, image, url.
 */
function blush_get_services( $limit = -1 ) {
	$posts = blush_query( 'blush_service', $limit );
	$out   = array();
	if ( $posts ) {
		foreach ( $posts as $i => $p ) {
			$link  = get_post_meta( $p->ID, '_blush_link', true );
			$out[] = array(
				'title'   => get_the_title( $p ),
				'excerpt' => has_excerpt( $p ) ? get_the_excerpt( $p ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $p->post_content ) ), 18 ),
				'image'   => blush_thumb( $p->ID, 'blush-card', 'service-' . ( ( $i % 6 ) + 1 ) . '.svg' ),
				'url'     => $link ? $link : get_permalink( $p ),
			);
		}
		return $out;
	}
	foreach ( blush_samples( 'services' ) as $s ) {
		$out[] = array(
			'title'   => $s['title'],
			'excerpt' => $s['excerpt'],
			'image'   => blush_asset_img( $s['image'] ),
			'url'     => '',
		);
	}
	return $limit > 0 ? array_slice( $out, 0, $limit ) : $out;
}

/**
 * Service names for the booking select field.
 *
 * @return string[]
 */
function blush_service_options() {
	return wp_list_pluck( blush_get_services(), 'title' );
}

/**
 * Testimonials for display.
 *
 * @return array[] name, city, quote, image.
 */
function blush_get_testimonials() {
	$posts = blush_query( 'blush_testimonial' );
	$out   = array();
	if ( $posts ) {
		foreach ( $posts as $p ) {
			$out[] = array(
				'name'  => get_the_title( $p ),
				'city'  => get_post_meta( $p->ID, '_blush_city', true ),
				'quote' => $p->post_content,
				'image' => blush_thumb( $p->ID, 'thumbnail', 'avatar.svg' ),
			);
		}
		return $out;
	}
	foreach ( blush_samples( 'testimonials' ) as $s ) {
		$s['image'] = blush_asset_img( 'avatar.svg' );
		$out[]      = $s;
	}
	return $out;
}

/**
 * Team members for display.
 *
 * @return array[] name, role, image, social.
 */
function blush_get_team() {
	$posts = blush_query( 'blush_team' );
	$out   = array();
	if ( $posts ) {
		foreach ( $posts as $i => $p ) {
			$social = array();
			foreach ( array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube' ) as $net ) {
				$social[ $net ] = get_post_meta( $p->ID, '_blush_' . $net, true );
			}
			$out[] = array(
				'name'   => get_the_title( $p ),
				'role'   => get_post_meta( $p->ID, '_blush_role', true ),
				'image'  => blush_thumb( $p->ID, 'blush-card', 'team-' . ( ( $i % 4 ) + 1 ) . '.svg' ),
				'social' => array_filter( $social ),
			);
		}
		return $out;
	}
	foreach ( blush_samples( 'team' ) as $s ) {
		$s['image']  = blush_asset_img( $s['image'] );
		$s['social'] = array();
		$out[]       = $s;
	}
	return $out;
}

/**
 * Pricing plans for display.
 *
 * @return array[]
 */
function blush_get_plans() {
	$posts = blush_query( 'blush_plan' );
	if ( ! $posts ) {
		return blush_samples( 'plans' );
	}
	$out = array();
	foreach ( $posts as $p ) {
		$out[] = array(
			'title'    => get_the_title( $p ),
			'excerpt'  => has_excerpt( $p ) ? get_the_excerpt( $p ) : '',
			'price'    => get_post_meta( $p->ID, '_blush_price', true ),
			'period'   => get_post_meta( $p->ID, '_blush_period', true ),
			'features' => get_post_meta( $p->ID, '_blush_features', true ),
			'featured' => (bool) get_post_meta( $p->ID, '_blush_featured', true ),
			'btn_text' => get_post_meta( $p->ID, '_blush_btn_text', true ),
			'btn_url'  => get_post_meta( $p->ID, '_blush_btn_url', true ),
			'note'     => get_post_meta( $p->ID, '_blush_note', true ),
		);
	}
	return $out;
}

/**
 * FAQs for display.
 *
 * @return array[] question, answer.
 */
function blush_get_faqs() {
	$posts = blush_query( 'blush_faq' );
	if ( ! $posts ) {
		return blush_samples( 'faqs' );
	}
	$out = array();
	foreach ( $posts as $p ) {
		$out[] = array(
			'question' => get_the_title( $p ),
			'answer'   => $p->post_content,
		);
	}
	return $out;
}

/**
 * Split a textarea value into clean lines.
 *
 * @param string $text Text.
 * @return string[]
 */
function blush_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ), 'strlen' ) );
}

/**
 * Convert a video page URL into something the video modal can play.
 *
 * @param string $url YouTube, Vimeo or direct file URL.
 * @return array type (iframe|file|''), src.
 */
function blush_video_source( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return array(
			'type' => '',
			'src'  => '',
		);
	}
	if ( preg_match( '~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,20})~', $url, $m ) ) {
		return array(
			'type' => 'iframe',
			'src'  => 'https://www.youtube-nocookie.com/embed/' . $m[1] . '?autoplay=1&rel=0',
		);
	}
	if ( preg_match( '~vimeo\.com/(?:video/)?([0-9]{4,15})~', $url, $m ) ) {
		return array(
			'type' => 'iframe',
			'src'  => 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1',
		);
	}
	if ( preg_match( '~\.(mp4|webm|ogv)(\?.*)?$~i', $url ) ) {
		return array(
			'type' => 'file',
			'src'  => $url,
		);
	}
	return array(
		'type' => '',
		'src'  => '',
	);
}

/**
 * Safe map embed source from the Customizer value (a URL or pasted iframe code).
 *
 * @return string URL or empty string.
 */
function blush_map_src() {
	$value = trim( blush_mod( 'map_url' ) );
	if ( '' === $value ) {
		return '';
	}
	if ( preg_match( '/src=["\']([^"\']+)["\']/i', $value, $m ) ) {
		$value = html_entity_decode( $m[1], ENT_QUOTES );
	}
	$value = esc_url_raw( $value, array( 'https' ) );
	$host  = strtolower( (string) wp_parse_url( $value, PHP_URL_HOST ) );
	$ok    = array( 'www.google.com', 'maps.google.com', 'google.com', 'www.openstreetmap.org', 'www.bing.com' );
	return in_array( $host, $ok, true ) ? $value : '';
}

/**
 * Copyright line.
 *
 * @return string
 */
function blush_copyright() {
	return str_replace(
		array( '{year}', '{site}' ),
		array( wp_date( 'Y' ), get_bloginfo( 'name' ) ),
		blush_mod( 'copyright' )
	);
}

/**
 * Section heading block (small label + heading).
 *
 * @param string $eyebrow Small italic label.
 * @param string $title   Heading text.
 * @param string $class   Extra class.
 * @param string $id      Optional heading id.
 */
function blush_heading( $eyebrow, $title, $class = '', $id = '' ) {
	echo '<div class="section-head ' . esc_attr( $class ) . '">';
	if ( '' !== $eyebrow ) {
		echo '<p class="eyebrow">' . esc_html( $eyebrow ) . '</p>';
	}
	if ( '' !== $title ) {
		printf( '<h2 class="section-title"%s>%s</h2>', $id ? ' id="' . esc_attr( $id ) . '"' : '', esc_html( $title ) );
	}
	echo '</div>';
}

/**
 * Post meta line for blog posts.
 */
function blush_posted_on() {
	printf(
		'<span class="posted-on"><time datetime="%1$s">%2$s</time></span><span class="byline"> &middot; %3$s</span>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_html( get_the_author() )
	);
}

/**
 * Numbered pagination.
 */
function blush_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => __( 'Previous', 'blush-salon' ),
			'next_text' => __( 'Next', 'blush-salon' ),
		)
	);
}
