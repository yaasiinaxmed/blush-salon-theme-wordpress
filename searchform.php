<?php
/**
 * Search form.
 *
 * @package Blush_Salon
 */

$blush_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $blush_id ); ?>"><?php esc_html_e( 'Search for:', 'blush-salon' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $blush_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'blush-salon' ); ?>">
	<button type="submit" class="btn btn--accent"><?php esc_html_e( 'Search', 'blush-salon' ); ?></button>
</form>
