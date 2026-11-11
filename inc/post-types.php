<?php
/**
 * Custom post types and their admin list columns.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build labels for a post type.
 *
 * @param string $singular Singular name.
 * @param string $plural   Plural name.
 * @return array
 */
function blush_cpt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		/* translators: %s: post type singular name. */
		'add_new_item'       => sprintf( __( 'Add New %s', 'blush-salon' ), $singular ),
		/* translators: %s: post type singular name. */
		'edit_item'          => sprintf( __( 'Edit %s', 'blush-salon' ), $singular ),
		/* translators: %s: post type singular name. */
		'new_item'           => sprintf( __( 'New %s', 'blush-salon' ), $singular ),
		/* translators: %s: post type singular name. */
		'view_item'          => sprintf( __( 'View %s', 'blush-salon' ), $singular ),
		/* translators: %s: post type plural name. */
		'search_items'       => sprintf( __( 'Search %s', 'blush-salon' ), $plural ),
		/* translators: %s: post type plural name. */
		'all_items'          => sprintf( __( 'All %s', 'blush-salon' ), $plural ),
		/* translators: %s: post type plural name. */
		'not_found'          => sprintf( __( 'No %s found.', 'blush-salon' ), $plural ),
		/* translators: %s: post type plural name. */
		'not_found_in_trash' => sprintf( __( 'No %s found in Trash.', 'blush-salon' ), $plural ),
	);
}

/**
 * Register post types.
 */
function blush_register_post_types() {
	// Content types (edited by the site owner).
	register_post_type(
		'blush_service',
		array(
			'labels'       => blush_cpt_labels( __( 'Service', 'blush-salon' ), __( 'Services', 'blush-salon' ) ),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-heart',
			'menu_position' => 21,
			'rewrite'      => array( 'slug' => 'service' ),
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	$private_content = array(
		'public'              => false,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'exclude_from_search' => true,
		'show_in_rest'        => false,
	);

	register_post_type(
		'blush_testimonial',
		array_merge(
			$private_content,
			array(
				'labels'        => blush_cpt_labels( __( 'Testimonial', 'blush-salon' ), __( 'Testimonials', 'blush-salon' ) ),
				'menu_icon'     => 'dashicons-format-quote',
				'menu_position' => 22,
				'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			)
		)
	);
	register_post_type(
		'blush_team',
		array_merge(
			$private_content,
			array(
				'labels'        => blush_cpt_labels( __( 'Team Member', 'blush-salon' ), __( 'Team', 'blush-salon' ) ),
				'menu_icon'     => 'dashicons-groups',
				'menu_position' => 23,
				'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		)
	);
	register_post_type(
		'blush_plan',
		array_merge(
			$private_content,
			array(
				'labels'        => blush_cpt_labels( __( 'Pricing Plan', 'blush-salon' ), __( 'Pricing Plans', 'blush-salon' ) ),
				'menu_icon'     => 'dashicons-tag',
				'menu_position' => 24,
				'supports'      => array( 'title', 'excerpt', 'page-attributes' ),
			)
		)
	);
	register_post_type(
		'blush_faq',
		array_merge(
			$private_content,
			array(
				'labels'        => blush_cpt_labels( __( 'FAQ', 'blush-salon' ), __( 'FAQs', 'blush-salon' ) ),
				'menu_icon'     => 'dashicons-editor-help',
				'menu_position' => 25,
				'supports'      => array( 'title', 'editor', 'page-attributes' ),
			)
		)
	);

	// Entries created by visitors through the forms. Private, admin only, cannot be added by hand.
	$entries = array(
		'public'              => false,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'exclude_from_search' => true,
		'show_in_rest'        => false,
		'capability_type'     => 'post',
		'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap'        => true,
		'supports'            => array( 'title' ),
	);
	register_post_type(
		'blush_appointment',
		array_merge(
			$entries,
			array(
				'labels'        => blush_cpt_labels( __( 'Appointment', 'blush-salon' ), __( 'Appointments', 'blush-salon' ) ),
				'menu_icon'     => 'dashicons-calendar-alt',
				'menu_position' => 26,
			)
		)
	);
	register_post_type(
		'blush_message',
		array_merge(
			$entries,
			array(
				'labels'        => blush_cpt_labels( __( 'Message', 'blush-salon' ), __( 'Messages', 'blush-salon' ) ),
				'menu_icon'     => 'dashicons-email-alt',
				'menu_position' => 27,
			)
		)
	);
	register_post_type(
		'blush_subscriber',
		array_merge(
			$entries,
			array(
				'labels'        => blush_cpt_labels( __( 'Subscriber', 'blush-salon' ), __( 'Newsletter', 'blush-salon' ) ),
				'menu_icon'     => 'dashicons-megaphone',
				'menu_position' => 28,
			)
		)
	);
}
add_action( 'init', 'blush_register_post_types' );

/**
 * Flush rewrite rules once after the theme is switched on, so service links work.
 */
function blush_flush_rewrites() {
	blush_register_post_types();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'blush_flush_rewrites' );

/**
 * Admin columns per entry type: column key => array( label, meta key ).
 *
 * @param string $post_type Post type.
 * @return array
 */
function blush_entry_columns( $post_type ) {
	$columns = array(
		'blush_appointment' => array(
			'blush_name'    => array( __( 'Name', 'blush-salon' ), '_blush_name' ),
			'blush_phone'   => array( __( 'Phone', 'blush-salon' ), '_blush_phone' ),
			'blush_email'   => array( __( 'Email', 'blush-salon' ), '_blush_email' ),
			'blush_date'    => array( __( 'Appointment date', 'blush-salon' ), '_blush_date' ),
			'blush_time'    => array( __( 'Time', 'blush-salon' ), '_blush_time' ),
			'blush_service' => array( __( 'Service', 'blush-salon' ), '_blush_service' ),
		),
		'blush_message'     => array(
			'blush_name'    => array( __( 'Name', 'blush-salon' ), '_blush_name' ),
			'blush_email'   => array( __( 'Email', 'blush-salon' ), '_blush_email' ),
			'blush_phone'   => array( __( 'Phone', 'blush-salon' ), '_blush_phone' ),
			'blush_company' => array( __( 'Company', 'blush-salon' ), '_blush_company' ),
			'blush_subject' => array( __( 'Subject', 'blush-salon' ), '_blush_subject' ),
		),
		'blush_subscriber'  => array(
			'blush_email' => array( __( 'Email', 'blush-salon' ), '_blush_email' ),
		),
		'blush_team'        => array(
			'blush_role' => array( __( 'Role', 'blush-salon' ), '_blush_role' ),
		),
		'blush_testimonial' => array(
			'blush_city' => array( __( 'City / label', 'blush-salon' ), '_blush_city' ),
		),
		'blush_plan'        => array(
			'blush_price'    => array( __( 'Price', 'blush-salon' ), '_blush_price' ),
			'blush_period'   => array( __( 'Period', 'blush-salon' ), '_blush_period' ),
			'blush_featured' => array( __( 'Highlighted', 'blush-salon' ), '_blush_featured' ),
		),
	);
	return isset( $columns[ $post_type ] ) ? $columns[ $post_type ] : array();
}

/**
 * Hook admin columns for all types that define them.
 */
function blush_admin_columns_init() {
	foreach ( array( 'blush_appointment', 'blush_message', 'blush_subscriber', 'blush_team', 'blush_testimonial', 'blush_plan' ) as $type ) {
		add_filter( 'manage_' . $type . '_posts_columns', 'blush_admin_columns' );
		add_action( 'manage_' . $type . '_posts_custom_column', 'blush_admin_column_content', 10, 2 );
	}
}
add_action( 'admin_init', 'blush_admin_columns_init' );

/**
 * Add columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function blush_admin_columns( $columns ) {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$type   = $screen && ! empty( $screen->post_type ) ? $screen->post_type : '';
	$extra  = blush_entry_columns( $type );
	if ( ! $extra ) {
		return $columns;
	}
	$new = array();
	foreach ( $columns as $key => $label ) {
		if ( 'date' === $key ) {
			foreach ( $extra as $col => $data ) {
				$new[ $col ] = $data[0];
			}
			if ( in_array( $type, array( 'blush_appointment', 'blush_message', 'blush_subscriber' ), true ) ) {
				$label = __( 'Received', 'blush-salon' );
			}
		}
		$new[ $key ] = $label;
	}
	return $new;
}

/**
 * Print column values.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function blush_admin_column_content( $column, $post_id ) {
	$extra = blush_entry_columns( get_post_type( $post_id ) );
	if ( ! isset( $extra[ $column ] ) ) {
		return;
	}
	$value = get_post_meta( $post_id, $extra[ $column ][1], true );
	if ( 'blush_featured' === $column ) {
		echo $value ? esc_html__( 'Yes', 'blush-salon' ) : '&mdash;';
		return;
	}
	if ( '' === (string) $value ) {
		echo '&mdash;';
	} elseif ( 'blush_email' === $column ) {
		printf( '<a href="%s">%s</a>', esc_url( 'mailto:' . $value ), esc_html( $value ) );
	} elseif ( 'blush_phone' === $column ) {
		printf( '<a href="%s">%s</a>', esc_url( blush_tel( $value ) ), esc_html( $value ) );
	} else {
		echo esc_html( $value );
	}
}
