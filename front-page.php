<?php
/**
 * Template Name: Front Page
 *
 * The home page: hero, quick booking, about, CTA, services, booking form,
 * why choose us, testimonials, partner logos and video banner.
 *
 * @package Blush_Salon
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( blush_is_elementor_page() ) {
		?>
		<div class="elementor-page-content">
			<?php the_content(); ?>
		</div>
		<?php
	} else {
		get_template_part( 'template-parts/home-hero' );
		get_template_part( 'template-parts/booking-bar' );
		get_template_part( 'template-parts/section-about' );
		get_template_part( 'template-parts/section-cta' );
		get_template_part( 'template-parts/section-services', null, array( 'limit' => 6 ) );
		get_template_part( 'template-parts/page-content' );
		get_template_part( 'template-parts/section-appointment' );
		get_template_part( 'template-parts/section-why' );
		get_template_part( 'template-parts/section-testimonials' );
		get_template_part( 'template-parts/section-logos' );
		get_template_part( 'template-parts/section-video' );
	}

endwhile;

get_footer();
