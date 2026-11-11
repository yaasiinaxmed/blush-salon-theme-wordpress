<?php
/**
 * Full-width map.
 *
 * @package Blush_Salon
 */

$blush_map = blush_map_src();
?>
<section class="map" aria-label="<?php esc_attr_e( 'Map', 'blush-salon' ); ?>">
	<?php if ( $blush_map ) : ?>
		<iframe src="<?php echo esc_url( $blush_map ); ?>" title="<?php esc_attr_e( 'Our location on the map', 'blush-salon' ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
	<?php else : ?>
		<div class="map__placeholder" style="background-image:url('<?php echo esc_url( blush_asset_img( 'map-placeholder.svg' ) ); ?>')">
			<?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
				<p><?php esc_html_e( 'Map placeholder. Add your Google Maps embed link in Appearance > Customize > Blush Salon Options > Contact Page.', 'blush-salon' ); ?></p>
			<?php elseif ( '' !== blush_mod( 'address' ) ) : ?>
				<p><?php echo nl2br( esc_html( blush_mod( 'address' ) ) ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
