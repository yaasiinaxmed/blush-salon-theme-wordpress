<?php
/**
 * 404 page.
 *
 * @package Blush_Salon
 */

get_header();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'title'    => __( 'Page not found', 'blush-salon' ),
		'subtitle' => __( 'Error 404', 'blush-salon' ),
	)
);
?>
<div class="section">
	<div class="container container--narrow error-404">
		<p class="error-404__code" aria-hidden="true">404</p>
		<p class="lead"><?php esc_html_e( 'Sorry, we could not find this page. It may have been moved or deleted.', 'blush-salon' ); ?></p>
		<?php get_search_form(); ?>
		<p><a class="btn btn--accent" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the home page', 'blush-salon' ); ?></a></p>
	</div>
</div>
<?php
get_footer();
