<?php
/**
 * Team grid.
 *
 * @package Blush_Salon
 */

$blush_team = blush_get_team();
if ( ! $blush_team ) {
	return;
}
?>
<section class="section team" id="team" aria-labelledby="team-title">
	<div class="container">
		<?php blush_heading( blush_mod( 'team_eyebrow' ), blush_mod( 'team_title' ), 'section-head--center reveal', 'team-title' ); ?>
		<ul class="card-grid card-grid--4">
			<?php foreach ( $blush_team as $blush_member ) : ?>
				<li class="team-card reveal">
					<?php /* translators: %s: team member name. */ ?>
					<img class="team-card__img" src="<?php echo esc_url( $blush_member['image'] ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Photo of %s', 'blush-salon' ), $blush_member['name'] ) ); ?>" width="600" height="600" loading="lazy">
					<div class="team-card__body">
						<h3 class="team-card__name"><?php echo esc_html( $blush_member['name'] ); ?></h3>
						<?php if ( '' !== (string) $blush_member['role'] ) : ?>
							<p class="team-card__role"><?php echo esc_html( $blush_member['role'] ); ?></p>
						<?php endif; ?>
						<?php blush_social_list( $blush_member['social'], 'social-list--center' ); ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
