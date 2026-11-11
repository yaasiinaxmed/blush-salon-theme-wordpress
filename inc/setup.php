<?php
/**
 * Theme setup: supports, menus, widget areas, assets.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme supports and menus.
 */
function blush_setup() {
	load_theme_textdomain( 'blush-salon', BLUSH_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 300,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	add_image_size( 'blush-card', 800, 800, true );
	add_image_size( 'blush-wide', 1600, 900, true );

	register_nav_menus(
		array(
			'primary'        => __( 'Primary Menu (header)', 'blush-salon' ),
			'footer-company' => __( 'Footer: Company column', 'blush-salon' ),
			'footer-support' => __( 'Footer: Support column', 'blush-salon' ),
			'footer-legal'   => __( 'Footer: Bottom bar links', 'blush-salon' ),
		)
	);
}
add_action( 'after_setup_theme', 'blush_setup' );

/**
 * Content width for embeds.
 */
function blush_content_width() {
	$GLOBALS['content_width'] = 800;
}
add_action( 'after_setup_theme', 'blush_content_width', 0 );

/**
 * Widget areas.
 */
function blush_widgets_init() {
	$shared = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	);
	register_sidebar(
		array_merge(
			$shared,
			array(
				'name'        => __( 'Blog Sidebar', 'blush-salon' ),
				'id'          => 'sidebar-1',
				'description' => __( 'Shown next to blog posts and archives.', 'blush-salon' ),
			)
		)
	);
	register_sidebar(
		array_merge(
			$shared,
			array(
				'name'        => __( 'Footer Extra', 'blush-salon' ),
				'id'          => 'footer-1',
				'description' => __( 'Optional widgets shown above the copyright bar.', 'blush-salon' ),
			)
		)
	);
}
add_action( 'widgets_init', 'blush_widgets_init' );

/**
 * Validate a hex color and return it, or the fallback.
 *
 * @param string $key Theme option key.
 * @return string
 */
function blush_color( $key ) {
	$fields = blush_fields();
	$color  = sanitize_hex_color( blush_mod( $key ) );
	return $color ? $color : $fields[ $key ][3];
}

/**
 * Front-end assets.
 */
function blush_enqueue_assets() {
	wp_enqueue_style( 'blush-fonts', BLUSH_URI . '/assets/css/fonts.css', array(), BLUSH_VERSION );
	wp_enqueue_style( 'blush-main', BLUSH_URI . '/assets/css/main.css', array( 'blush-fonts' ), BLUSH_VERSION );
	wp_enqueue_style( 'blush-style', get_stylesheet_uri(), array( 'blush-main' ), BLUSH_VERSION );

	$css = sprintf(
		':root{--c-primary:%1$s;--c-accent:%2$s;--c-blush:%3$s;}',
		blush_color( 'color_primary' ),
		blush_color( 'color_accent' ),
		blush_color( 'color_blush' )
	);
	wp_add_inline_style( 'blush-main', $css );

	wp_enqueue_script( 'blush-main', BLUSH_URI . '/assets/js/main.js', array(), BLUSH_VERSION, true );
	wp_localize_script(
		'blush-main',
		'blushSalon',
		array(
			'openMenu'    => __( 'Open menu', 'blush-salon' ),
			'closeMenu'   => __( 'Close menu', 'blush-salon' ),
			/* translators: %d: slide group number. */
			'goToSlide'   => __( 'Go to testimonials page %d', 'blush-salon' ),
			'videoTitle'  => __( 'Video player', 'blush-salon' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'blush_enqueue_assets' );

/**
 * Mark the document as JS-capable as early as possible (used by reveal animations).
 */
function blush_js_class() {
	echo "<script>document.documentElement.className = document.documentElement.className.replace( 'no-js', 'js' );</script>\n";
}
add_action( 'wp_head', 'blush_js_class', 0 );

/**
 * Preload the two main font files.
 */
function blush_preload_fonts() {
	foreach ( array( 'domine-latin.woff2', 'inter-latin.woff2' ) as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( BLUSH_URI . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'blush_preload_fonts', 1 );

/**
 * Add a toggle button after primary menu links that have a submenu.
 *
 * @param string   $item_output Item HTML.
 * @param WP_Post  $item        Menu item.
 * @param int      $depth       Depth.
 * @param stdClass $args        Menu args.
 * @return string
 */
function blush_submenu_toggle( $item_output, $item, $depth, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$item_output .= sprintf(
			'<button type="button" class="submenu-toggle" aria-expanded="false"><span class="screen-reader-text">%s</span>%s</button>',
			/* translators: %s: menu item title. */
			esc_html( sprintf( __( 'Open submenu of %s', 'blush-salon' ), wp_strip_all_tags( $item->title ) ) ),
			blush_get_icon( 'chevron' )
		);
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'blush_submenu_toggle', 10, 4 );

/**
 * Simple fallback when no primary menu is assigned.
 */
function blush_primary_menu_fallback() {
	echo '<ul id="primary-menu" class="menu">';
	printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'Home', 'blush-salon' ) );
	$pages = get_pages(
		array(
			'parent'      => 0,
			'number'      => 5,
			'sort_column' => 'menu_order,post_title',
			'exclude'     => array_filter( array( (int) get_option( 'page_on_front' ) ) ),
		)
	);
	foreach ( (array) $pages as $page ) {
		printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( get_permalink( $page ) ), esc_html( get_the_title( $page ) ) );
	}
	echo '</ul>';
}

/**
 * Body classes.
 *
 * @param array $classes Classes.
 * @return array
 */
function blush_body_classes( $classes ) {
	if ( is_active_sidebar( 'sidebar-1' ) && ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) ) {
		$classes[] = 'has-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'blush_body_classes' );

/**
 * Shorter excerpts and a clean "more" sign.
 *
 * @return int
 */
function blush_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'blush_excerpt_length' );

/**
 * Excerpt more.
 *
 * @return string
 */
function blush_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'blush_excerpt_more' );

/**
 * When the site front shows the latest posts, use the blog template, not the designed home page.
 *
 * @param string $template Template path.
 * @return string
 */
function blush_frontpage_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template', 'blush_frontpage_template' );
