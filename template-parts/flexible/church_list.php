<?php
$selected_churches = get_sub_field('selected_churches');

if (empty($selected_churches)) {
	return;
}

$selected_churches = get_sub_field('selected_churches');

if (empty($selected_churches)) {
	return;
}

foreach ($selected_churches as $church) {
	get_template_part(
			'template-parts/page-builder',
			null,
			[
					'post_id' => $church->ID,
			]
	);
}

wp_reset_postdata();
