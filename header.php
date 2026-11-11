<?php
/**
 * Site header.
 *
 * @package Blush_Salon
 */

?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'blush-salon' ); ?></a>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<div class="site-branding">
			<?php blush_logo(); ?>
		</div>

		<button type="button" class="nav-toggle" aria-controls="site-navigation" aria-expanded="false">
			<span class="screen-reader-text nav-toggle__label"><?php esc_html_e( 'Open menu', 'blush-salon' ); ?></span>
			<span class="nav-toggle__open"><?php blush_icon( 'menu' ); ?></span>
			<span class="nav-toggle__close"><?php blush_icon( 'close' ); ?></span>
		</button>

		<nav class="main-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'blush-salon' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => 'blush_primary_menu_fallback',
				)
			);
			?>
			<a class="btn btn--accent main-nav__cta" href="<?php echo esc_url( home_url( '/#book' ) ); ?>"><?php esc_html_e( 'Book Now', 'blush-salon' ); ?></a>
		</nav>
	</div>
</header>

<main id="main" class="site-main">
