<?php
//* template-parts/flexible/text_image.php

$title = get_sub_field('title');
$level = get_sub_field('level');
$align = get_sub_field('align');

$image_type = get_sub_field('image_type');

$image = get_sub_field('image');

$clergy_rank = get_sub_field('clergy_rank');
$clergy_subtitle = get_sub_field('clergy_subtitle');


$clergy_name = get_sub_field('clergy_name');
$clergy_post = get_sub_field('clergy_post');

$image_position = get_sub_field('image_position');
$text = get_sub_field('text');

$lightbox = get_sub_field('lightbox');
$caption = get_sub_field('caption');

$image_ratio = get_sub_field('image_ratio');

if (!$image_ratio) {
	$image_ratio = 'ratio-default';
}

//* Значения по умолчанию
if (!$level) {
	$level = 'h2';
}

if (!$align) {
	$align = 'left';
}

if (!$image_position) {
	$image_position = 'left';
}
?>

<section class="text-image-block">
	<?php
	get_template_part(
			'template-parts/partials/title',
			null,
			[
					'title' => $title,
					'level' => $level,
					'align' => $align,
					'anchor' => '',

			]
	);
	?>

	<?php if ($image_type === 'clergy') : ?>

		<div class="info-content__image current <?= esc_attr($image_position); ?> <?= esc_attr($image_ratio); ?>">

			<?php
			get_template_part(
					'template-parts/partials/clergy-card',
					null,
					[
							'image'    => $image,
							'rank'     => $clergy_rank,
							'subtitle' => $clergy_subtitle,
							'name'     => $clergy_name,
							'post'     => $clergy_post,
					]
			);
			?>

		</div>

	<?php elseif ($image) : ?>

		<div class="info-content__image current <?= esc_attr($image_position); ?> <?= esc_attr($image_ratio); ?>">

			<?php if ($lightbox) : ?>

				<div class="gallery-image">
					<div class="gallery-image__item">
						<a class="gallery-image__link" href="<?= esc_url($image['url']); ?>" target="_blank" rel="noopener">

							<?= theme_image($image); ?>

							<span class="icon-zoom">
							<?php theme_icon('zoom'); ?>
						</span>

						</a>
					</div>
				</div>

			<?php else : ?>

				<?= theme_image($image); ?>

			<?php endif; ?>


			<?php if ($caption && !empty($image['caption'])) : ?>

				<div class="caption-image">
					<?= esc_html($image['caption']); ?>
				</div>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php if ($text) : ?>

		<div class="info-content__text">
			<?= wp_kses_post($text); ?>
		</div>

	<?php endif; ?>

</section>