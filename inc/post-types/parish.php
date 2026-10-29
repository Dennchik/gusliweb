<?php
/**
 * inc/post-types.php
 */
//* Регистрация типа записи "Приходы в локации"

declare(strict_types=1);

function theme_register_post_types(): void
{

	register_post_type(
			'parish',
			[
					'labels' => [
							'name'               => 'Приходы в локации',
							'singular_name'      => 'Приход',
							'add_new'            => 'Добавить приход',
							'add_new_item'       => 'Добавить новый приход',
							'edit_item'          => 'Редактировать приход',
							'new_item'           => 'Новый приход',
							'view_item'          => 'Просмотреть приход',
							'search_items'       => 'Искать приходы',
							'not_found'          => 'Приходы не найдены',
							'not_found_in_trash' => 'В корзине приходов нет',
					],

					'public'             => true,
					'menu_icon'          => 'dashicons-location-alt',
					'has_archive'        => true,
					'rewrite'            => [
							'slug' => 'parishes',
					],

					'supports' => [
							'title',
							'editor',
							'thumbnail',
					],
			]
	);

}

add_action('init', 'theme_register_post_types');