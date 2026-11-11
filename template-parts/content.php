<?php
/**
 * Post card used in the blog, archives and search results.
 *
 * @package Blush_Salon
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<a class="post-card__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large', array( 'alt' => '' ) ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( blush_asset_img( 'post-placeholder.svg' ) ); ?>" alt="" width="1200" height="750" loading="lazy">
		<?php endif; ?>
	</a>
	<div class="post-card__body">
		<?php if ( 'post' === get_post_type() ) : ?>
			<p class="post-card__meta"><?php blush_posted_on(); ?></p>
		<?php endif; ?>
		<?php the_title( '<h2 class="post-card__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<div class="post-card__excerpt"><?php the_excerpt(); ?></div>
		<a class="post-card__more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'blush-salon' ); ?>
			<span class="screen-reader-text"><?php echo esc_html( get_the_title() ); ?></span>
			<?php blush_icon( 'arrow' ); ?>
		</a>
	</div>
</article>
