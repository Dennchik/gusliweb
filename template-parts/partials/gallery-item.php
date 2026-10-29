<?php
/** template-parts/partials/gallery-item.php */

declare(strict_types=1);

$image = $args['image'] ?? null;
$caption = $args['caption'] ?? false;

if (empty($image)) {
	return;
}
?>

<?= theme_image($image); ?>

<?php if ($caption && !empty($image['caption'])) : ?>

	<div class="gallery__caption">
		<?= esc_html($image['caption']); ?>
	</div>

<?php endif; ?>