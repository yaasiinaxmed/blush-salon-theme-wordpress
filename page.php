<?php
/**
 * Default page template.
 *
 * @package Blush_Salon
 */

get_header();

while ( have_posts() ) :
	the_post();
	if ( blush_is_builder_page() ) {
		?>
		<div class="elementor-page-content">
			<?php the_content(); ?>
		</div>
		<?php
	} else {
		get_template_part( 'template-parts/page-hero' );
		?>
		<div class="section">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'container container--narrow' ); ?>>
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'blush-salon' ) . '">',
							'after'  => '</nav>',
						)
					);
					?>
				</div>
				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</article>
		</div>
		<?php
	}
endwhile;

get_footer();
