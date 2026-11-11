<?php
/**
 * Template Name: Pricing
 *
 * Page hero, pricing plans, why choose us and the FAQ accordion.
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
		get_template_part( 'template-parts/page-hero', null, array( 'fallback' => 'hero-pricing.svg' ) );
		get_template_part( 'template-parts/section-pricing' );
		get_template_part( 'template-parts/page-content' );
		get_template_part( 'template-parts/section-why' );
		get_template_part( 'template-parts/section-faq' );
	}

endwhile;

get_footer();
