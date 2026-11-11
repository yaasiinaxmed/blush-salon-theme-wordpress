<?php
/**
 * Archives: categories, tags, dates, authors.
 *
 * @package Blush_Salon
 */

get_header();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'title'    => get_the_archive_title(),
		'subtitle' => wp_strip_all_tags( get_the_archive_description() ),
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
