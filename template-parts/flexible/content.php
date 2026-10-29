<?php
//* template-parts/flexible/content.php

declare(strict_types=1);


$title = get_sub_field('title');
$level = get_sub_field('level');
$align = get_sub_field('align');

$content = get_sub_field('content');

//* Значения по умолчанию
if (!$content && !$title) {
	return;
}


if (!$level) {
	$level = 'h2';
}


if (!$align) {
	$align = 'left';
}

?>


<section class="content-block">


	<?php
	if ($title) {

		get_template_part(
				'template-parts/partials/title',
				null,
				[
						'title' => $title,
						'level' => $level,
						'align' => $align,
				]
		);

	}
	?>


	<?php if ($content) : ?>

		<div class="content-block__text">

			<?= wp_kses_post($content); ?>

		</div>

	<?php endif; ?>


</section>