<?php
/**
 * Demo content importer.
 *
 * It only runs when an administrator clicks the button. It can be run many
 * times: it never creates the same thing twice and never changes content that
 * already exists.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remember to show the setup notice after the theme is switched on.
 */
function blush_setup_notice_on_activation() {
	if ( ! get_option( 'blush_demo_imported' ) ) {
		update_option( 'blush_show_setup_notice', 1, false );
	}
}
add_action( 'after_switch_theme', 'blush_setup_notice_on_activation' );

/**
 * URL of the setup page.
 *
 * @return string
 */
function blush_setup_page_url() {
	return admin_url( 'themes.php?page=blush-setup' );
}

/**
 * Print the import button form.
 *
 * @param string $label Button label.
 */
function blush_import_button( $label ) {
	printf( '<form method="post" action="%s" style="display:inline-block;margin:0 8px 0 0">', esc_url( admin_url( 'admin-post.php' ) ) );
	echo '<input type="hidden" name="action" value="blush_import_demo">';
	wp_nonce_field( 'blush_import_demo', 'blush_import_nonce' );
	printf( '<button type="submit" class="button button-primary">%s</button>', esc_html( $label ) );
	echo '</form>';
}

/**
 * Admin notice with the import button.
 */
function blush_setup_admin_notice() {
	if ( ! current_user_can( 'edit_theme_options' ) || ! get_option( 'blush_show_setup_notice' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'appearance_page_blush-setup' === $screen->id ) {
		return;
	}
	$dismiss = wp_nonce_url( add_query_arg( 'blush_dismiss_setup', '1', admin_url( 'themes.php' ) ), 'blush_dismiss_setup' );
	echo '<div class="notice notice-info"><p><strong>' . esc_html__( 'Welcome to Blush Salon!', 'blush-salon' ) . '</strong> ';
	echo esc_html__( 'Click the button to create the demo pages (Home, About, Services, Pricing, Contact), the menus and some sample content. Nothing that already exists will be changed.', 'blush-salon' ) . '</p><p>';
	blush_import_button( __( 'Create demo pages & menus', 'blush-salon' ) );
	printf( '<a class="button" href="%s">%s</a>', esc_url( $dismiss ), esc_html__( 'No thanks, hide this', 'blush-salon' ) );
	echo '</p></div>';
}
add_action( 'admin_notices', 'blush_setup_admin_notice' );

/**
 * Dismiss the notice.
 */
function blush_dismiss_setup_notice() {
	if ( isset( $_GET['blush_dismiss_setup'] ) && current_user_can( 'edit_theme_options' ) ) {
		check_admin_referer( 'blush_dismiss_setup' );
		delete_option( 'blush_show_setup_notice' );
		wp_safe_redirect( admin_url( 'themes.php' ) );
		exit;
	}
}
add_action( 'admin_init', 'blush_dismiss_setup_notice' );

/**
 * Appearance > Blush Salon Setup page.
 */
function blush_setup_menu() {
	add_theme_page( __( 'Blush Salon Setup', 'blush-salon' ), __( 'Blush Salon Setup', 'blush-salon' ), 'edit_theme_options', 'blush-setup', 'blush_setup_page' );
}
add_action( 'admin_menu', 'blush_setup_menu' );

/**
 * Render the setup page.
 */
function blush_setup_page() {
	$log = get_transient( 'blush_import_log_' . get_current_user_id() );
	echo '<div class="wrap"><h1>' . esc_html__( 'Blush Salon Setup', 'blush-salon' ) . '</h1>';

	if ( is_array( $log ) ) {
		delete_transient( 'blush_import_log_' . get_current_user_id() );
		echo '<div class="notice notice-success"><p><strong>' . esc_html__( 'Demo import finished.', 'blush-salon' ) . '</strong></p><ul style="list-style:disc;margin-left:20px">';
		foreach ( $log as $line ) {
			echo '<li>' . esc_html( $line ) . '</li>';
		}
		echo '</ul><p>';
		printf( '<a class="button button-primary" href="%s">%s</a> ', esc_url( home_url( '/' ) ), esc_html__( 'View the site', 'blush-salon' ) );
		printf( '<a class="button" href="%s">%s</a>', esc_url( admin_url( 'customize.php' ) ), esc_html__( 'Open the Customizer', 'blush-salon' ) );
		echo '</p></div>';
	}

	echo '<div class="card" style="max-width:760px"><h2>' . esc_html__( '1. Create demo pages & menus', 'blush-salon' ) . '</h2>';
	echo '<p>' . esc_html__( 'This creates the pages Home, About Us, Services, Pricing Plan, Contact Us and Blog, sets Home as the front page, builds the header and footer menus and adds a few sample services, testimonials, team members, pricing plans and FAQs.', 'blush-salon' ) . '</p>';
	echo '<p>' . esc_html__( 'It is safe to click more than once. It never creates duplicates and never changes pages, menus or settings that already exist.', 'blush-salon' ) . '</p><p>';
	blush_import_button( __( 'Create demo pages & menus', 'blush-salon' ) );
	echo '</p></div>';

	echo '<div class="card" style="max-width:760px"><h2>' . esc_html__( '2. Make it yours', 'blush-salon' ) . '</h2><ul style="list-style:disc;margin-left:20px">';
	$items = array(
		array( admin_url( 'customize.php' ), __( 'Customizer: logo, colors, texts, images, phone, email, address, social links, map and video.', 'blush-salon' ) ),
		array( admin_url( 'edit.php?post_type=blush_service' ), __( 'Services', 'blush-salon' ) ),
		array( admin_url( 'edit.php?post_type=blush_testimonial' ), __( 'Testimonials', 'blush-salon' ) ),
		array( admin_url( 'edit.php?post_type=blush_team' ), __( 'Team', 'blush-salon' ) ),
		array( admin_url( 'edit.php?post_type=blush_plan' ), __( 'Pricing Plans', 'blush-salon' ) ),
		array( admin_url( 'edit.php?post_type=blush_faq' ), __( 'FAQs', 'blush-salon' ) ),
		array( admin_url( 'edit.php?post_type=blush_appointment' ), __( 'Appointments received from the website', 'blush-salon' ) ),
		array( admin_url( 'edit.php?post_type=blush_message' ), __( 'Messages received from the contact form', 'blush-salon' ) ),
	);
	foreach ( $items as $item ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $item[0] ), esc_html( $item[1] ) );
	}
	echo '</ul></div></div>';
}

/**
 * Find or create one demo page.
 *
 * @param string $key      Demo key.
 * @param string $title    Page title.
 * @param string $template Page template file ('' for default).
 * @param string $subtitle Hero subtitle.
 * @param array  $log      Log lines (by reference).
 * @return int Page ID or 0.
 */
function blush_demo_page( $key, $title, $template, $subtitle, &$log ) {
	$found = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_blush_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $key, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	if ( ! $found && $template ) {
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
	}
	if ( $found ) {
		/* translators: %s: page title. */
		$log[] = sprintf( __( 'Page "%s" already exists. Not changed.', 'blush-salon' ), get_the_title( $found[0] ) );
		return (int) $found[0];
	}

	$meta = array( '_blush_demo' => $key );
	if ( $template ) {
		$meta['_wp_page_template'] = $template;
	}
	if ( $subtitle ) {
		$meta['_blush_hero_subtitle'] = $subtitle;
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => '',
			'meta_input'   => $meta,
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		/* translators: %s: page title. */
		$log[] = sprintf( __( 'Could not create page "%s".', 'blush-salon' ), $title );
		return 0;
	}
	/* translators: %s: page title. */
	$log[] = sprintf( __( 'Page "%s" created.', 'blush-salon' ), $title );
	return (int) $id;
}

/**
 * Create a menu (only when no menu with that name exists) and fill it.
 *
 * @param string $name     Menu name.
 * @param string $location Theme location.
 * @param array  $items    Items: array( title, url or page ID, children[] ).
 * @param array  $log      Log lines (by reference).
 */
function blush_demo_menu( $name, $location, $items, &$log ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu ) {
		$menu_id = (int) $menu->term_id;
		/* translators: %s: menu name. */
		$log[] = sprintf( __( 'Menu "%s" already exists. Not changed.', 'blush-salon' ), $name );
	} else {
		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			/* translators: %s: menu name. */
			$log[] = sprintf( __( 'Could not create menu "%s".', 'blush-salon' ), $name );
			return;
		}
		foreach ( $items as $item ) {
			$parent = blush_demo_menu_item( $menu_id, $item, 0 );
			if ( $parent && ! empty( $item[2] ) ) {
				foreach ( $item[2] as $child ) {
					blush_demo_menu_item( $menu_id, $child, $parent );
				}
			}
		}
		/* translators: %s: menu name. */
		$log[] = sprintf( __( 'Menu "%s" created.', 'blush-salon' ), $name );
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}
	if ( empty( $locations[ $location ] ) || ! wp_get_nav_menu_object( $locations[ $location ] ) ) {
		$locations[ $location ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

/**
 * Add one menu item.
 *
 * @param int   $menu_id Menu ID.
 * @param array $item    array( title, url or page ID ).
 * @param int   $parent  Parent item ID.
 * @return int Item ID or 0.
 */
function blush_demo_menu_item( $menu_id, $item, $parent ) {
	$args = array(
		'menu-item-title'     => $item[0],
		'menu-item-status'    => 'publish',
		'menu-item-parent-id' => $parent,
	);
	if ( is_int( $item[1] ) ) {
		if ( $item[1] < 1 ) {
			return 0;
		}
		$args['menu-item-object-id'] = $item[1];
		$args['menu-item-object']    = get_post_type( $item[1] );
		$args['menu-item-type']      = 'post_type';
	} else {
		$args['menu-item-url']  = $item[1];
		$args['menu-item-type'] = 'custom';
	}
	$id = wp_update_nav_menu_item( $menu_id, 0, $args );
	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * Insert sample entries for a post type, only when that type is completely empty.
 *
 * @param string $post_type Post type.
 * @param string $label     Human name for the log.
 * @param array  $entries   wp_insert_post() arrays.
 * @param array  $log       Log lines (by reference).
 * @return int[] IDs of created posts.
 */
function blush_demo_entries( $post_type, $label, $entries, &$log ) {
	$counts = (array) wp_count_posts( $post_type );
	if ( array_sum( array_map( 'intval', $counts ) ) > 0 ) {
		/* translators: %s: content type name. */
		$log[] = sprintf( __( 'Sample %s: some already exist. Nothing added.', 'blush-salon' ), $label );
		return array();
	}
	$ids = array();
	foreach ( $entries as $order => $entry ) {
		$entry['post_type']   = $post_type;
		$entry['post_status'] = 'publish';
		$entry['menu_order']  = $order + 1;
		if ( ! isset( $entry['meta_input'] ) ) {
			$entry['meta_input'] = array();
		}
		$entry['meta_input']['_blush_demo'] = '1';
		$id                                 = wp_insert_post( $entry, true );
		if ( ! is_wp_error( $id ) && $id ) {
			$ids[] = (int) $id;
		}
	}
	/* translators: 1: number of items, 2: content type name. */
	$log[] = sprintf( __( '%1$d sample %2$s added.', 'blush-salon' ), count( $ids ), $label );
	return $ids;
}

/**
 * Run the import.
 *
 * @return array Log lines.
 */
function blush_run_demo_import() {
	$log = array();

	// 1. Pages.
	$home     = blush_demo_page( 'home', __( 'Home', 'blush-salon' ), 'front-page.php', '', $log );
	$about    = blush_demo_page( 'about', __( 'About Us', 'blush-salon' ), 'page-templates/template-about.php', __( 'Get to know our salon and our team.', 'blush-salon' ), $log );
	$services = blush_demo_page( 'services', __( 'Services', 'blush-salon' ), 'page-templates/template-services.php', __( 'Everything you need to look your best.', 'blush-salon' ), $log );
	$pricing  = blush_demo_page( 'pricing', __( 'Pricing Plan', 'blush-salon' ), 'page-templates/template-pricing.php', __( 'Simple packages with clear prices.', 'blush-salon' ), $log );
	$contact  = blush_demo_page( 'contact', __( 'Contact Us', 'blush-salon' ), 'page-templates/template-contact.php', __( 'We would love to hear from you.', 'blush-salon' ), $log );
	$blog     = blush_demo_page( 'blog', __( 'Blog', 'blush-salon' ), '', '', $log );

	// 2. Front page (only when the site still shows the latest posts, or the old front page is gone).
	$front_now = (int) get_option( 'page_on_front' );
	if ( $home && ( 'page' !== get_option( 'show_on_front' ) || ! $front_now || 'publish' !== get_post_status( $front_now ) ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home );
		$log[] = __( 'Home is now the front page.', 'blush-salon' );
		$posts_page = (int) get_option( 'page_for_posts' );
		if ( $blog && ( ! $posts_page || 'publish' !== get_post_status( $posts_page ) ) ) {
			update_option( 'page_for_posts', $blog );
			$log[] = __( 'Blog is now the posts page.', 'blush-salon' );
		}
	} elseif ( $front_now === $home ) {
		$log[] = __( 'Home is already the front page.', 'blush-salon' );
	} else {
		$log[] = __( 'Your site already has another front page. It was not changed. You can change it in Settings > Reading.', 'blush-salon' );
	}

	// 3. Sample content.
	$service_entries = array();
	foreach ( blush_samples( 'services' ) as $s ) {
		$service_entries[] = array(
			'post_title'   => $s['title'],
			'post_excerpt' => $s['excerpt'],
			'post_content' => $s['content'],
		);
	}
	$service_ids = blush_demo_entries( 'blush_service', __( 'services', 'blush-salon' ), $service_entries, $log );

	$entries = array();
	foreach ( blush_samples( 'testimonials' ) as $s ) {
		$entries[] = array(
			'post_title'   => $s['name'],
			'post_content' => $s['quote'],
			'meta_input'   => array( '_blush_city' => $s['city'] ),
		);
	}
	blush_demo_entries( 'blush_testimonial', __( 'testimonials', 'blush-salon' ), $entries, $log );

	$entries = array();
	foreach ( blush_samples( 'team' ) as $s ) {
		$entries[] = array(
			'post_title' => $s['name'],
			'meta_input' => array( '_blush_role' => $s['role'] ),
		);
	}
	blush_demo_entries( 'blush_team', __( 'team members', 'blush-salon' ), $entries, $log );

	$entries = array();
	foreach ( blush_samples( 'plans' ) as $s ) {
		$entries[] = array(
			'post_title'   => $s['title'],
			'post_excerpt' => $s['excerpt'],
			'meta_input'   => array_filter(
				array(
					'_blush_price'    => $s['price'],
					'_blush_period'   => $s['period'],
					'_blush_features' => $s['features'],
					'_blush_featured' => $s['featured'] ? '1' : '',
					'_blush_btn_text' => $s['btn_text'],
					'_blush_note'     => $s['note'],
				)
			),
		);
	}
	blush_demo_entries( 'blush_plan', __( 'pricing plans', 'blush-salon' ), $entries, $log );

	$entries = array();
	foreach ( blush_samples( 'faqs' ) as $s ) {
		$entries[] = array(
			'post_title'   => $s['question'],
			'post_content' => $s['answer'],
		);
	}
	blush_demo_entries( 'blush_faq', __( 'FAQs', 'blush-salon' ), $entries, $log );

	// 4. Menus.
	$service_children = array();
	if ( ! $service_ids ) {
		$service_ids = wp_list_pluck( blush_query( 'blush_service', 3 ), 'ID' );
	}
	foreach ( array_slice( $service_ids, 0, 3 ) as $sid ) {
		$service_children[] = array( get_the_title( $sid ), (int) $sid );
	}
	$pages_children = array(
		array( __( 'Pricing Plan', 'blush-salon' ), $pricing ),
		array( __( 'Our Team', 'blush-salon' ), $about ? get_permalink( $about ) . '#team' : '#' ),
		array( __( 'FAQ', 'blush-salon' ), $pricing ? get_permalink( $pricing ) . '#faq' : '#' ),
		array( __( 'Blog', 'blush-salon' ), $blog ),
	);
	blush_demo_menu(
		'Blush Primary Menu',
		'primary',
		array(
			array( __( 'Homepage', 'blush-salon' ), $home ),
			array( __( 'About Us', 'blush-salon' ), $about ),
			array( __( 'Services', 'blush-salon' ), $services, $service_children ),
			array( __( 'Contact Us', 'blush-salon' ), $contact ),
			array( __( 'Pages', 'blush-salon' ), '#', $pages_children ),
		),
		$log
	);
	blush_demo_menu(
		'Blush Footer Company',
		'footer-company',
		array(
			array( __( 'About Us', 'blush-salon' ), $about ),
			array( __( 'Our Team', 'blush-salon' ), $about ? get_permalink( $about ) . '#team' : '#' ),
			array( __( 'Services', 'blush-salon' ), $services ),
			array( __( 'Pricing Plan', 'blush-salon' ), $pricing ),
			array( __( 'Blog', 'blush-salon' ), $blog ),
		),
		$log
	);
	blush_demo_menu(
		'Blush Footer Support',
		'footer-support',
		array(
			array( __( 'FAQ', 'blush-salon' ), $pricing ? get_permalink( $pricing ) . '#faq' : '#' ),
			array( __( 'Book an Appointment', 'blush-salon' ), home_url( '/#book' ) ),
			array( __( 'Contact Us', 'blush-salon' ), $contact ),
		),
		$log
	);

	// 5. Placeholder social links, only for networks that were never set.
	$placeholders = array(
		'social_facebook'  => 'https://www.facebook.com/',
		'social_twitter'   => 'https://x.com/',
		'social_instagram' => 'https://www.instagram.com/',
		'social_linkedin'  => 'https://www.linkedin.com/',
	);
	$mods         = get_theme_mods();
	$added        = false;
	foreach ( $placeholders as $key => $url ) {
		if ( ! is_array( $mods ) || ! array_key_exists( $key, $mods ) ) {
			set_theme_mod( $key, $url );
			$added = true;
		}
	}
	if ( $added ) {
		$log[] = __( 'Placeholder social links added. Change them in the Customizer > Blush Salon Options > Social Links.', 'blush-salon' );
	}

	flush_rewrite_rules();
	update_option( 'blush_demo_imported', time(), false );
	delete_option( 'blush_show_setup_notice' );

	return $log;
}

/**
 * Handle the button click.
 */
function blush_handle_demo_import() {
	if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'publish_pages' ) ) {
		wp_die( esc_html__( 'You are not allowed to do this.', 'blush-salon' ), '', array( 'response' => 403 ) );
	}
	check_admin_referer( 'blush_import_demo', 'blush_import_nonce' );

	$log = blush_run_demo_import();
	set_transient( 'blush_import_log_' . get_current_user_id(), $log, 5 * MINUTE_IN_SECONDS );

	wp_safe_redirect( blush_setup_page_url() );
	exit;
}
add_action( 'admin_post_blush_import_demo', 'blush_handle_demo_import' );
