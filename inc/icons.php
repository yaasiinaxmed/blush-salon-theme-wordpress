<?php
/**
 * Small inline SVG icon set drawn for this theme.
 *
 * @package Blush_Salon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get an inline SVG icon.
 *
 * @param string $name Icon name.
 * @return string Safe SVG markup (static strings only).
 */
function blush_get_icon( $name ) {
	$stroke = array(
		'phone'     => '<path d="M5 3h4l2 5-2.5 1.5a11 11 0 0 0 6 6L16 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 5a2 2 0 0 1 2-2z"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
		'pin'       => '<path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.800-7 11-7 11z"/><circle cx="12" cy="10" r="2.500"/>',
		'check'     => '<path d="m5 12.500 4.500 4.500L19 7"/>',
		'arrow'     => '<path d="M4 12h16M14 6l6 6-6 6"/>',
		'chevron'   => '<path d="m6 9 6 6 6-6"/>',
		'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
		'shop'      => '<path d="M4 10v10h16V10M3 10l2-6h14l2 6a3 3 0 0 1-6 0 3 3 0 0 1-6 0 3 3 0 0 1-6 0zM10 20v-5h4v5"/>',
		'sparkle'   => '<path d="M12 3l2 6.500L20 12l-6 2.500L12 21l-2-6.500L4 12l6-2.500z"/>',
		'leaf'      => '<path d="M5 19c0-9 5-14 15-14 0 10-5 15-13 14M5 19c2-5 5-8 9-10"/>',
		'diamond'   => '<path d="M7 4h10l4 5-9 11L3 9zM3 9h18M9 4l-2 5 5 11 5-11-2-5"/>',
		'scissors'  => '<circle cx="6" cy="6" r="2.500"/><circle cx="6" cy="18" r="2.500"/><path d="M8 7.500 20 18M8 16.500 20 6"/>',
		'eye'       => '<path d="M2 12s3.600-6.500 10-6.500S22 12 22 12s-3.600 6.500-10 6.500S2 12 2 12z"/><circle cx="12" cy="12" r="2.800"/>',
		'target'    => '<circle cx="12" cy="12" r="8.500"/><circle cx="12" cy="12" r="4.500"/><circle cx="12" cy="12" r="1"/>',
		'star'      => '<path d="M12 4v16M5 8l14 8M19 8 5 16"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'     => '<path d="M6 6l12 12M18 6 6 18"/>',
		'instagram' => '<rect x="4" y="4" width="16" height="16" rx="4.500"/><circle cx="12" cy="12" r="3.500"/><path d="M16.800 7.200h.010"/>',
		'twitter'   => '<path d="M5 5l14 14M19 5 5 19"/>',
	);
	$fill   = array(
		'play'     => '<path d="M8 5.500v13l11-6.500z"/>',
		'facebook' => '<path d="M13.500 21v-7h2.300l.500-3h-2.800V9.300c0-.900.400-1.500 1.600-1.500h1.400V5.200c-.600-.100-1.400-.200-2.200-.200-2.300 0-3.800 1.400-3.800 3.900V11H8v3h2.500v7z"/>',
		'linkedin' => '<path d="M5 9.500h3V19H5zM6.500 5a1.700 1.700 0 1 1 0 3.400 1.700 1.700 0 0 1 0-3.400zM10.500 9.500h2.900v1.300c.500-.900 1.600-1.600 3-1.600 2.400 0 3.600 1.500 3.600 4.200V19h-3v-5c0-1.300-.500-2.100-1.600-2.100s-1.900.800-1.900 2.100v5h-3z"/>',
		'youtube'  => '<path d="M4.500 6.500h15A2.500 2.500 0 0 1 22 9v6a2.500 2.500 0 0 1-2.500 2.500h-15A2.500 2.500 0 0 1 2 15V9a2.500 2.500 0 0 1 2.500-2.500zM10 9.200v5.600l5-2.800z" fill-rule="evenodd"/>',
		'tiktok'   => '<path d="M13 4h2.600c.200 1.800 1.300 3 3.100 3.200v2.600c-1.200 0-2.200-.300-3.100-.900v5.300A4.800 4.800 0 1 1 10.800 9.400v2.700a2.200 2.200 0 1 0 2.200 2.200z"/>',
	);

	if ( isset( $stroke[ $name ] ) ) {
		return '<svg class="icon icon-' . $name . '" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $stroke[ $name ] . '</svg>';
	}
	if ( isset( $fill[ $name ] ) ) {
		return '<svg class="icon icon-' . $name . '" viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true" focusable="false">' . $fill[ $name ] . '</svg>';
	}
	return '';
}

/**
 * Echo an icon.
 *
 * @param string $name Icon name.
 */
function blush_icon( $name ) {
	// The markup comes from a fixed list in blush_get_icon(), never from user input.
	echo blush_get_icon( $name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
