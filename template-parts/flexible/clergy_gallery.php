<?php
/*
 * Flexible block: Галерея духовенства
 */

declare(strict_types=1);

$clergy_items = get_sub_field('clergy_items');

if (!$clergy_items) {
	return;
}
?>

<div class="clergy-gallery">

	<?php foreach ($clergy_items as $clergy): ?>

		<?php
		get_template_part(
				'template-parts/partials/clergy-card',
				null,
				[
						'clergy' => $clergy,
				]
		);
		?>

	<?php endforeach; ?>

</div>