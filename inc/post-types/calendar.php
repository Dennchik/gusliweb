<?php
/**
 * inc/post-types/calendar.php
 */

declare(strict_types=1);


/**
 * Регистрация типа записи "Календарь"
 */

function register_calendar_post_type(): void
{
	register_post_type('calendar', [
			'labels' => [
					'name'               => 'Православный Календарь',
					'menu_name'          => 'Календарь',
					'all_items'          => 'Все события',
					'singular_name'      => 'Событие',
					'add_new'            => 'Добавить событие',
					'add_new_item'       => 'Добавить новое событие',
					'edit_item'          => 'Редактировать событие',
					'view_item'          => 'Просмотреть событие',
					'search_items'       => 'Искать события',
					'not_found'          => 'События не найдены',
					'not_found_in_trash' => 'Событий в корзине нет',
			],

			'public' => true,

			'show_in_rest' => true,

			'has_archive' => true,

			'supports' => [
					'title',
					'editor',
			],

			'menu_icon' => 'dashicons-calendar-alt',

			'rewrite' => [
					'slug' => 'calendar',
			],
	]);
}

add_action('init', 'register_calendar_post_type');