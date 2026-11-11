<?php
/**
 * Main template: blog index and fallback for everything else.
 *
 * @package Blush_Salon
 */

get_header();

$blush_title = __( 'Blog', 'blush-salon' );
if ( is_home() && ! is_front_page() && get_option( 'page_for_posts' ) ) {
	$blush_title = get_the_title( (int) get_option( 'page_for_posts' ) );
}
get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'title'    => $blush_title,
		'subtitle' => get_bloginfo( 'description', 'display' ),
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
