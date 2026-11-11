<?php
/**
 * Video / play banner with a modal player.
 *
 * @package Blush_Salon
 */

$blush_video = blush_video_source( blush_mod( 'video_url' ) );
?>
<section class="video-banner" style="background-image:url('<?php echo esc_url( blush_img( 'video_image', 'video-bg.svg' ) ); ?>')" aria-labelledby="video-title">
	<div class="container video-banner__inner reveal">
		<?php if ( $blush_video['type'] ) : ?>
			<button type="button" class="play-btn" data-video-open data-video-type="<?php echo esc_attr( $blush_video['type'] ); ?>" data-video-src="<?php echo esc_url( $blush_video['src'] ); ?>" aria-haspopup="dialog">
				<?php blush_icon( 'play' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Play video', 'blush-salon' ); ?></span>
			</button>
		<?php endif; ?>
		<h2 class="video-banner__title" id="video-title"><?php echo esc_html( blush_mod( 'video_title' ) ); ?></h2>
		<p><?php echo esc_html( blush_mod( 'video_text' ) ); ?></p>
		<?php if ( '' !== blush_mod( 'video_btn_text' ) ) : ?>
			<a class="btn btn--accent" href="<?php echo esc_url( blush_link( 'video_btn_url', 'about' ) ); ?>"><?php echo esc_html( blush_mod( 'video_btn_text' ) ); ?></a>
		<?php endif; ?>
	</div>
</section>

<?php if ( $blush_video['type'] ) : ?>
	<dialog class="video-modal" data-video-modal aria-label="<?php esc_attr_e( 'Video player', 'blush-salon' ); ?>">
		<button type="button" class="video-modal__close" data-video-close>
			<?php blush_icon( 'close' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Close video', 'blush-salon' ); ?></span>
		</button>
		<div class="video-modal__frame" data-video-frame></div>
	</dialog>
<?php endif; ?>
