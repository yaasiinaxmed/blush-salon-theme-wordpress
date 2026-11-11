<?php
/**
 * Template Name: Contact
 *
 * Page hero, contact details, message form and a full-width map.
 *
 * @package Blush_Salon
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( blush_has_builder_content() ) {
		?>
		<div class="elementor-page-content">
			<?php the_content(); ?>
		</div>
		<?php
	} else {
		get_template_part( 'template-parts/page-hero', null, array( 'fallback' => 'hero-contact.svg' ) );
		get_template_part( 'template-parts/section-contact' );
		get_template_part( 'template-parts/page-content' );
		get_template_part( 'template-parts/section-map' );
	}

endwhile;

get_footer();
