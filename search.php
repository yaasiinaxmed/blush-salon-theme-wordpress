<?php
/**
 * Search results.
 *
 * @package Blush_Salon
 */

get_header();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		/* translators: %s: search words. */
		'title'    => sprintf( esc_html__( 'Search results for: %s', 'blush-salon' ), esc_html( get_search_query() ) ),
		'subtitle' => '',
	)
);
?>
<div class="section">
	<div class="container content-layout">
		<div class="content-layout__main">
			<?php if ( have_posts() ) : ?>
				<div class="post-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/content' );
					endwhile;
					?>
				</div>
				<?php blush_pagination(); ?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/content-none' ); ?>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</div>
<?php
get_footer();
