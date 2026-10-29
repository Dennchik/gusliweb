<?php
//* template-parts/flexible/gallery.php

/**
	* Плюс в будущем, если захочешь добавить:
	* класс к картинке;
	* подпись под фото;
	* дату;
	* кнопку скачать;
	* иконку увеличения;
	*меняем только gallery-item.php, а не весь блок галереи.
*/

$title = get_sub_field('title');
$level = get_sub_field('level');
$images = get_sub_field('images');
$columns = get_sub_field('columns');
$lightbox = get_sub_field('lightbox');
$caption = get_sub_field('caption');

//* Значения по умолчанию
$level = $level ?: 'h2';
$columns = $columns ?: '3';

//* Разрешенные значения
$allowed_levels = ['h2', 'h3', 'h4', 'h5', 'h6'];
if (!in_array($level, $allowed_levels, true)) {
	$level = 'h2';
}
$allowed_columns = ['2', '3', '4'];
if (!in_array($columns, $allowed_columns, true)) {
	$columns = '3';
} ?>

<section class="gallery-block">
	<?php
	get_template_part(
			'template-parts/partials/title',
			null,
			[
					'title' => $title,
					'level' => $level,
					'align' => 'left',
					'anchor' => '',
			]
	); ?>

	<?php if (!empty($images)) : ?>
		<div class="gallery-image gallery-image--<?= esc_attr($columns); ?>">
			<div class="gallery-image__body">
				<?php foreach ($images as $image) : ?>
					<div class="gallery-image__item">

						<?php if ($lightbox && !empty($image['url'])) : ?>

							<a class="gallery-image__link"
							   href="<?= esc_url($image['url']); ?>"
							   data-fancybox="gallery-<?= get_the_ID(); ?>">

								<?php
								get_template_part(
										'template-parts/partials/gallery-item',
										null,
										[
												'image' => $image,
										]
								);
								?>
								<span class="icon-zoom">
									<?php theme_icon('zoom'); ?>
								</span>
							</a>

						<?php else : ?>
							<div class="gallery-image__link">
								<?php
								get_template_part(
										'template-parts/partials/gallery-item',
										null,
										[
												'image' => $image,
										]
								);
								?>
							</div>

						<?php endif; ?>

						<?php if ($caption && !empty($image['caption'])) : ?>
							<div class="caption-image">
								<?= esc_html($image['caption']); ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</section>