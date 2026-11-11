<?php
/**
 * FAQ accordion in two columns.
 *
 * @package Blush_Salon
 */

$blush_faqs = blush_get_faqs();
if ( ! $blush_faqs ) {
	return;
}
$blush_columns = array_chunk( $blush_faqs, (int) ceil( count( $blush_faqs ) / 2 ) );
$blush_n       = 0;
?>
<section class="section faq" id="faq" aria-labelledby="faq-title">
	<div class="container">
		<?php blush_heading( blush_mod( 'faq_eyebrow' ), blush_mod( 'faq_title' ), 'section-head--center reveal', 'faq-title' ); ?>
		<div class="faq__cols" data-accordion>
			<?php foreach ( $blush_columns as $blush_column ) : ?>
				<div class="faq__col">
					<?php foreach ( $blush_column as $blush_faq ) : ?>
						<?php ++$blush_n; ?>
						<div class="faq-item reveal">
							<h3 class="faq-item__q">
								<button type="button" class="faq-item__btn" id="faq-btn-<?php echo (int) $blush_n; ?>" aria-expanded="true" aria-controls="faq-panel-<?php echo (int) $blush_n; ?>">
									<span><?php echo esc_html( $blush_faq['question'] ); ?></span>
									<span class="faq-item__icon" aria-hidden="true"><?php blush_icon( 'chevron' ); ?></span>
								</button>
							</h3>
							<div class="faq-item__panel" id="faq-panel-<?php echo (int) $blush_n; ?>" role="region" aria-labelledby="faq-btn-<?php echo (int) $blush_n; ?>">
								<div class="faq-item__a"><?php echo wp_kses_post( wpautop( $blush_faq['answer'] ) ); ?></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
