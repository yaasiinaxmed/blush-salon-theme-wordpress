<?php
/**
 * Template Name: About
 *
 * Page hero, who we are, values, CTA banner, team and partner logos.
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
		get_template_part( 'template-parts/page-hero', null, array( 'fallback' => 'page-hero.svg' ) );
		get_template_part( 'template-parts/section-about' );
		get_template_part( 'template-parts/section-values' );
		get_template_part( 'template-parts/page-content' );
		get_template_part( 'template-parts/section-cta' );
		get_template_part( 'template-parts/section-team' );
		get_template_part( 'template-parts/section-logos' );
	}

endwhile;

get_footer();
