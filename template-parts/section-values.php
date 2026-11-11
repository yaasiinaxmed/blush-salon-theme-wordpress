<?php
/**
 * Vision / Mission / Motto cards.
 *
 * @package Blush_Salon
 */

$blush_icons = array( 1 => 'eye', 2 => 'target', 3 => 'star' );
?>
<section class="section section--blush values" aria-labelledby="values-title">
	<div class="container">
		<?php blush_heading( blush_mod( 'values_eyebrow' ), blush_mod( 'values_title' ), 'section-head--center section-head--narrow reveal', 'values-title' ); ?>
		<ul class="card-grid card-grid--3">
			<?php foreach ( $blush_icons as $blush_i => $blush_icon_name ) : ?>
				<?php if ( '' !== blush_mod( 'value_' . $blush_i . '_title' ) ) : ?>
					<li class="value-card reveal">
						<span class="icon-circle" aria-hidden="true"><?php blush_icon( $blush_icon_name ); ?></span>
						<h3 class="value-card__title"><?php echo esc_html( blush_mod( 'value_' . $blush_i . '_title' ) ); ?></h3>
						<p><?php echo esc_html( blush_mod( 'value_' . $blush_i . '_text' ) ); ?></p>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
