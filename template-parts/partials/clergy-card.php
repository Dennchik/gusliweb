<?php
/**
 * template-parts/partials/clergy-card.php
 */

declare(strict_types=1);

$image = $args['image'] ?? null;
$rank = $args['rank'] ?? '';
$subtitle = $args['subtitle'] ?? '';
$name = $args['name'] ?? '';
$post = $args['post'] ?? '';

?>

<div class="clergy-card">

	<div class="clergy-card__body">
		<?php if ($image) : ?>
			<div class="clergy-card__image _img">
				<?= theme_image($image, 'medium'); ?>
			</div>
		<?php endif; ?>

		<div class="clergy-card__title-group">

			<?php if ($rank) : ?>
				<span class="clergy-card__rank">
						<?= esc_html($rank); ?>
					</span>
			<?php endif; ?>

			<?php if ($subtitle) : ?>
				<span class="clergy-card__subtitle-rank">
						<?= esc_html($subtitle); ?>
					</span>
			<?php endif; ?>
		</div>


		<?php if ($name) : ?>
			<span class="clergy-card__name">
					<?= esc_html($name); ?>
				</span>
		<?php endif; ?>


		<?php if ($post) : ?>
			<div class="clergy-card__post">
				<?= esc_html($post); ?>
			</div>
		<?php endif; ?>
	</div>

</div>