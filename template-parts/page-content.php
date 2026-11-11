<?php
/**
 * Optional editor content of a page that uses one of the designed templates.
 *
 * @package Blush_Salon
 */

if ( ! blush_has_elementor_content() && '' === trim( (string) get_post_field( 'post_content', get_the_ID() ) ) ) {
	return;
}
?>
<section class="section section--tight">
	<div class="container <?php echo blush_has_elementor_content() ? 'container--full' : 'container--narrow'; ?> entry-content">
		<?php the_content(); ?>
	</div>
</section>
