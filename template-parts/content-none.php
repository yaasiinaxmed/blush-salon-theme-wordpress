<?php
/**
 * Shown when no content is found.
 *
 * @package Blush_Salon
 */

?>
<section class="no-results">
	<h2 class="section-title section-title--sm"><?php esc_html_e( 'Nothing found', 'blush-salon' ); ?></h2>
	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'Sorry, nothing matched your search. Please try again with different words.', 'blush-salon' ); ?></p>
	<?php else : ?>
		<p><?php esc_html_e( 'There is no content here yet.', 'blush-salon' ); ?></p>
	<?php endif; ?>
	<?php get_search_form(); ?>
</section>
