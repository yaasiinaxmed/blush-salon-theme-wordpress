<?php
/**
 * Template Name: Services
 *
 * Page hero, all services, why choose us, testimonials, partner logos and video banner.
 *
 * @package Blush_Salon
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( blush_has_elementor_content() ) {
		?>
		<div class="elementor-page-content">
			<?php the_content(); ?>
		</div>
		<?php
	} else {
		get_template_part( 'template-parts/page-hero', null, array( 'fallback' => 'hero-services.svg' ) );
		get_template_part( 'template-parts/section-services', null, array( 'show_button' => false ) );
		get_template_part( 'template-parts/page-content' );
		get_template_part( 'template-parts/section-why' );
		get_template_part( 'template-parts/section-testimonials' );
		get_template_part( 'template-parts/section-logos' );
		get_template_part( 'template-parts/section-video' );
	}

endwhile;

get_footer();
