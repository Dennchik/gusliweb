<?php
/**
 * inc/church.php
 */
//* Регистрация типа записи "Храмы в Благочинии"

declare(strict_types=1);

function register_church_post_type(): void
{
	register_post_type('church', array(
			'labels' => array(
					'name' => 'Храмы в Благочинии',
					'menu_name' => 'Храмы в Благочинии',
					'singular_name' => 'Храм в Благочинии',
					'add_new' => 'Добавить храм',
					'add_new_item' => 'Добавить новый храм',
					'edit_item' => 'Редактировать храм',
					'view_item' => 'Просмотреть храм',
					'search_items' => 'Искать храмы',
					'not_found' => 'Храмов не найдено',
					'not_found_in_trash' => 'Храмов в корзине не найдено',
			),

			'public' => true,
			'show_in_rest' => true,
			'has_archive' => true,
			'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'), // добавили page-attributes
			'menu_icon' => 'dashicons-location-alt',
			'rewrite' => array('slug' => 'hramy'),
	));
}

add_action('init', 'register_church_post_type');

