<?php
/** template-parts/partials/title.php */


$title  = $args['title'] ?? '';
$level  = $args['level'] ?? 'h2';
$align  = $args['align'] ?? 'left';
$anchor = $args['anchor'] ?? '';


// Если нет заголовка

if (empty($title)) {
	return;
}


// Разрешенные уровни

$allowed_levels = array(
	'h2',
	'h3',
	'h4',
	'h5',
	'h6'
);

if (!in_array($level, $allowed_levels, true)) {
	$level = 'h2';
}


// Разрешенное выравнивание

$allowed_align = array(
	'left',
	'center',
	'right'
);

if (!in_array($align, $allowed_align, true)) {
	$align = 'left';
}


// Очистка якоря

$anchor = sanitize_title($anchor);

?>


<<?php echo esc_attr($level); ?>
	class="title title--<?php echo esc_attr($align); ?>"
	<?php if ($anchor) : ?>
	id="<?php echo esc_attr($anchor); ?>"
	<?php endif; ?>>

	<span>
		<?php echo esc_html($title); ?>
	</span>

</<?php echo esc_attr($level); ?>>