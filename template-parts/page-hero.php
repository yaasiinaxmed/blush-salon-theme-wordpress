<?php
/**
 * Inner page hero: title, subtitle, background image.
 *
 * Args: title, subtitle, image (URL), fallback (file in assets/images).
 *
 * @package Blush_Salon
 */

$blush_args     = isset( $args ) && is_array( $args ) ? $args : array();
$blush_title    = isset( $blush_args['title'] ) ? $blush_args['title'] : get_the_title();
$blush_subtitle = isset( $blush_args['subtitle'] ) ? $blush_args['subtitle'] : '';
$blush_image    = isset( $blush_args['image'] ) ? $blush_args['image'] : '';
$blush_fallback = isset( $blush_args['fallback'] ) ? $blush_args['fallback'] : 'page-hero.svg';

if ( ! isset( $blush_args['subtitle'] ) && is_singular( 'page' ) ) {
	$blush_subtitle = get_post_meta( get_the_ID(), '_blush_hero_subtitle', true );
	if ( '' === $blush_subtitle ) {
		$blush_subtitle = blush_mod( 'page_hero_subtitle' );
	}
}
if ( '' === $blush_image && is_singular() && has_post_thumbnail() ) {
	$blush_image = get_the_post_thumbnail_url( get_the_ID(), 'blush-wide' );
}
if ( ! $blush_image ) {
	$blush_image = blush_img( 'page_hero_image', $blush_fallback );
}
?>
<header class="page-hero" style="background-image:url('<?php echo esc_url( $blush_image ); ?>')">
	<div class="container page-hero__inner">
		<h1 class="page-hero__title"><?php echo wp_kses_post( $blush_title ); ?></h1>
		<?php if ( '' !== (string) $blush_subtitle ) : ?>
			<p class="page-hero__subtitle"><?php echo esc_html( $blush_subtitle ); ?></p>
		<?php endif; ?>
	</div>
</header>
