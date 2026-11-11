<?php
/**
 * Blog sidebar.
 *
 * @package Blush_Salon
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'blush-salon' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
