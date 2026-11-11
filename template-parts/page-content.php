<?php
/**
 * Optional editor content of a page that uses one of the designed templates.
 *
 * @package Blush_Salon
 */

if ( '' === trim( (string) get_post_field( 'post_content', get_the_ID() ) ) ) {
	return;
}
?>
<section class="section section--tight">
	<div class="container container--narrow entry-content">
		<?php the_content(); ?>
	</div>
</section>
