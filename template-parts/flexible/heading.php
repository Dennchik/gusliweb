<?php
//* heading.php

$title = get_sub_field('title');
$level = get_sub_field('level');
$align = get_sub_field('align');
$anchor = get_sub_field('anchor');

//* Значения по умолчанию

if (!$level) {
  $level = 'h2';
}

if (!$align) {
  $align = 'left';
}


//* Подключаем общий заголовок

get_template_part(
    'template-parts/partials/title',
    null,
    array(
        'title' => $title,
        'level' => $level,
        'align' => $align,
        'anchor' => $anchor
    )
);
