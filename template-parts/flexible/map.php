<?php
//* template-parts/flexible/map.php

declare(strict_types=1);

$title = get_sub_field('title');
$level = get_sub_field('level');
$align = get_sub_field('align');


$parishes = new WP_Query(
		[
				'post_type'      => 'parish',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
		]
);


$map_points = [];


if ($parishes->have_posts()) {

	while ($parishes->have_posts()) {
		$parishes->the_post();

		$coordinates = get_field('parish_coordinates');

		if (!$coordinates) {
			continue;
		}


		$coords = explode(',', $coordinates);


		if (count($coords) < 2) {
			continue;
		}


		//* Фото прихода ACF
		$image_field = get_field('parish_image');

		$image = '';

		if ($image_field) {

			if (is_array($image_field)) {
				$image = $image_field['url'];
			} else {
				$image = wp_get_attachment_image_url(
						$image_field,
						'thumbnail'
				);
			}

		}


		//* Настоятель
		$priest = get_field('parish_rector');


		$map_points[] = [
				'title'  => get_the_title(),
				'lat'    => trim($coords[0]),
				'lng'    => trim($coords[1]),
				'image'  => $image ?: '',
				'priest' => $priest ?: '',
		];

	}

	wp_reset_postdata();

}


//* Значения по умолчанию

if (!$level) {
	$level = 'h2';
}


if (!$align) {
	$align = 'left';
}

?>


<section class="map-block">

	<?php
	get_template_part(
			'template-parts/partials/title',
			null,
			[
					'title'  => $title,
					'level'  => $level,
					'align'  => $align,
					'anchor' => '',
			]
	);
	?>


	<div class="map-block__body">

		<div
				class="map-block__content"
				data-map="<?= esc_attr(wp_json_encode($map_points)); ?>"
		></div>

	</div>


</section>