<?php
/**
 * template-parts/page-builder.php
 */

declare(strict_types=1);

$post_id = $args['post_id'] ?? get_the_ID();

if (!have_rows('page_builder', $post_id)) {
	return;
}

while (have_rows('page_builder', $post_id)) {
	the_row();

	$layout = get_row_layout();

	get_template_part(
			'template-parts/flexible/' . $layout
	);
}