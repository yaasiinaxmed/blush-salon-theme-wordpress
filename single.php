<?php
/**
 * Single post and single service.
 *
 * @package Blush_Salon
 */

get_header();

while ( have_posts() ) :
	the_post();
	$blush_is_service = 'blush_service' === get_post_type();
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'subtitle' => $blush_is_service ? ( has_excerpt() ? get_the_excerpt() : '' ) : get_the_date(),
			'fallback' => $blush_is_service ? 'hero-services.svg' : 'page-hero.svg',
		)
	);
	?>
	<div class="section">
		<div class="container content-layout">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'content-layout__main' ); ?>>
				<?php if ( ! $blush_is_service ) : ?>
					<p class="entry-meta"><?php blush_posted_on(); ?></p>
				<?php endif; ?>

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

				<?php if ( $blush_is_service ) : ?>
					<p class="entry-cta"><a class="btn btn--accent" href="<?php echo esc_url( home_url( '/#book' ) ); ?>"><?php blush_icon( 'calendar' ); ?><?php esc_html_e( 'Book this service', 'blush-salon' ); ?></a></p>
				<?php else : ?>
					<footer class="entry-footer">
						<?php the_tags( '<p class="tag-links"><span class="screen-reader-text">' . esc_html__( 'Tags:', 'blush-salon' ) . ' </span>', ' ', '</p>' ); ?>
					</footer>
					<?php
					the_post_navigation(
						array(
							'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous', 'blush-salon' ) . '</span> <span class="nav-title">%title</span>',
							'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'blush-salon' ) . '</span> <span class="nav-title">%title</span>',
						)
					);
					?>
				<?php endif; ?>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</article>
			<?php
			if ( ! $blush_is_service ) {
				get_sidebar();
			}
			?>
		</div>
	</div>
	<?php
endwhile;

get_footer();
