<?php
/**
 * Blush Salon functions and definitions.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BLUSH_VERSION', '1.1.1' );
define( 'BLUSH_DIR', get_template_directory() );
define( 'BLUSH_URI', get_template_directory_uri() );

require BLUSH_DIR . '/inc/config.php';
require BLUSH_DIR . '/inc/setup.php';
require BLUSH_DIR . '/inc/icons.php';
require BLUSH_DIR . '/inc/template-tags.php';
require BLUSH_DIR . '/inc/post-types.php';
require BLUSH_DIR . '/inc/meta-boxes.php';
require BLUSH_DIR . '/inc/customizer.php';
require BLUSH_DIR . '/inc/forms.php';
require BLUSH_DIR . '/inc/demo-import.php';
require BLUSH_DIR . '/inc/elementor.php';
require BLUSH_DIR . '/inc/elementor-demo.php';
