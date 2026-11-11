<?php
/**
 * Template Name: Elementor Full Width
 *
 * Full width page template designed for Elementor and block editors.
 * Preserves the theme's header and footer with complete edge-to-edge content area.
 *
 * @package Blush_Salon
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="elementor-full-width-content">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;

get_footer();
