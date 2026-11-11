<?php
/**
 * Editor / page builder content slot of a page that uses one of the designed templates.
 *
 * the_content() is always called while a page builder editor is open, because
 * Elementor, Beaver Builder, Divi, WPBakery etc. refuse to load without it.
 *
 * @package Blush_Salon
 */

$blush_has_text    = '' !== trim( (string) get_post_field( 'post_content', get_the_ID() ) );
$blush_has_builder = blush_has_builder_content();

if ( ! $blush_has_text && ! $blush_has_builder && ! blush_is_builder_editing() ) {
	return;
}
?>
<section class="section section--tight">
	<div class="container <?php echo $blush_has_builder ? 'container--full' : 'container--narrow'; ?> entry-content">
		<?php the_content(); ?>
	</div>
</section>
