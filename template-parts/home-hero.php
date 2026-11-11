<?php
/**
 * Home hero.
 *
 * @package Blush_Salon
 */

?>
<section class="hero" style="background-image:url('<?php echo esc_url( blush_img( 'hero_image', 'hero.svg' ) ); ?>')" aria-labelledby="hero-title">
	<div class="container hero__inner">
		<div class="hero__content">
			<h1 class="hero__title" id="hero-title"><?php echo esc_html( blush_mod( 'hero_title' ) ); ?></h1>
			<?php if ( '' !== blush_mod( 'hero_text' ) ) : ?>
				<p class="hero__text"><?php echo esc_html( blush_mod( 'hero_text' ) ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== blush_mod( 'hero_btn_text' ) ) : ?>
				<a class="btn btn--light btn--lg" href="<?php echo esc_url( blush_link( 'hero_btn_url', 'services', '#services' ) ); ?>"><?php echo esc_html( blush_mod( 'hero_btn_text' ) ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
