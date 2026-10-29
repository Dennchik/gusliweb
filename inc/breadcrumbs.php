<?php
/* inc/breadcrumbs.php*/

declare(strict_types=1);

/**
 * Вывод хлебных крошек.
 */
function theme_breadcrumbs(): void
{
	if (is_front_page()) {
		return;
	}

	$items = [];

	if (is_page()) {
		$items = theme_get_page_breadcrumbs();
	} elseif (is_single()) {
		$items = theme_get_news_breadcrumbs();
	} elseif (is_category()) {
		$items = theme_get_category_breadcrumbs();
	} elseif (is_search()) {
		$items = theme_get_search_breadcrumbs();
	} elseif (is_404()) {
		$items = theme_get_404_breadcrumbs();
	}

	if (!empty($items)) {
		theme_render_breadcrumbs($items);
	}
}


/**
 * Хлебные крошки страниц.
 *
 * Приоритет:
 *
 * 1. news_menu
 * 2. aside_menu
 * 3. иерархия WordPress
 */
function theme_get_page_breadcrumbs(): array
{
	$items = [
			[
					'title' => 'Главная',
					'url' => home_url('/'),
			],
	];


	/**
	 * 1. news_menu
	 */
	$menu_items = theme_get_menu_chain(
			'news_menu',
			get_the_ID()
	);


	if (!empty($menu_items)) {

		return array_merge(
				$items,
				$menu_items
		);

	}


	/**
	 * 2. aside_menu
	 */
	$menu_items = theme_get_menu_chain(
			'aside_menu',
			get_the_ID()
	);


	if (!empty($menu_items)) {

		return array_merge(
				$items,
				$menu_items
		);

	}

	/**
	 * 3. about_menu
	 */
	$menu_items = theme_get_menu_chain(
			'about_menu',
			get_the_ID()
	);


	if (!empty($menu_items)) {

		return array_merge(
				$items,
				$menu_items
		);

	}

	/**
	 * 4. Иерархия WordPress
	 */
	$ancestors = get_post_ancestors(
			get_the_ID()
	);


	if (!empty($ancestors)) {

		$ancestors = array_reverse($ancestors);

		foreach ($ancestors as $ancestor) {

			$items[] = [
					'title' => get_the_title($ancestor),
					'url' => get_permalink($ancestor),
			];

		}

	}


	$items[] = [
			'title' => get_the_title(),
			'url' => '',
	];


	return $items;
}


/**
 * Универсальное получение цепочки из меню.
 */
function theme_get_menu_chain(
		string $location,
		int    $page_id
): array
{

	$locations = get_nav_menu_locations();


	if (empty($locations[$location])) {
		return [];
	}


	$menu_items = wp_get_nav_menu_items(
			$locations[$location]
	);


	if (
			empty($menu_items)
			||
			is_wp_error($menu_items)
	) {
		return [];
	}


	$current_item = null;

	$page_url = get_permalink($page_id);


	foreach ($menu_items as $item) {


		if (
				$item->object === 'page'
				&&
				(int)$item->object_id === $page_id
		) {

			$current_item = $item;
			break;

		}


		if (
				$item->type === 'custom'
				&&
				untrailingslashit($item->url)
				===
				untrailingslashit($page_url)
		) {

			$current_item = $item;
			break;

		}

	}


	if (!$current_item) {
		return [];
	}


	$breadcrumbs = [];


	while ($current_item) {


		$url = '';


		if (
				$current_item->type === 'custom'
				&&
				$current_item->url !== '#'
		) {

			$url = $current_item->url;


		} elseif (
				$current_item->object === 'page'
				&&
				$current_item->object_id
		) {

			$url = get_permalink(
					$current_item->object_id
			);

		}


		$breadcrumbs[] = [
				'title' => $current_item->title,
				'url' => $url,
		];


		if (empty($current_item->menu_item_parent)) {
			break;
		}


		$parent_id = (int)$current_item->menu_item_parent;

		$current_item = null;


		foreach ($menu_items as $item) {

			if ((int)$item->ID === $parent_id) {

				$current_item = $item;
				break;

			}

		}

	}


	$breadcrumbs = array_reverse($breadcrumbs);


	if (!empty($breadcrumbs)) {
		$breadcrumbs[array_key_last($breadcrumbs)]['url'] = '';
	}


	return $breadcrumbs;
}


/**
 * Хлебные крошки одиночной новости.
 *
 * Главная → Новости Благочиния → 27 июля 2026
 */
function theme_get_news_breadcrumbs(): array
{
	$items = [
			[
					'title' => 'Главная',
					'url' => home_url('/'),
			],
	];


	$raw_date = get_field('дата');


	if ($raw_date) {

		$date = DateTime::createFromFormat(
				'YmdHi',
				$raw_date
		);


		if ($date) {

			$title = wp_date(
					'j F Y',
					$date->getTimestamp()
			);

		} else {

			$title = get_the_date('j F Y');

		}


	} else {

		$title = get_the_date('j F Y');

	}


	$menu_items = theme_get_news_menu_breadcrumbs(
			get_the_ID()
	);


	if (!empty($menu_items)) {

		return array_merge(
				$items,
				$menu_items,
				[
						[
								'title' => $title,
								'url' => '',
						],
				]
		);

	}


	$items[] = [
			'title' => $title,
			'url' => '',
	];


	return $items;
}

/**
 * Получение цепочки новости из news_menu.
 */
function theme_get_news_menu_breadcrumbs(int $post_id): array
{
	$locations = get_nav_menu_locations();


	if (empty($locations['news_menu'])) {
		return [];
	}


	$menu_items = wp_get_nav_menu_items(
			$locations['news_menu']
	);


	if (
			empty($menu_items)
			||
			is_wp_error($menu_items)
	) {
		return [];
	}


	$post_categories = wp_get_post_categories(
			$post_id,
			[
					'fields' => 'ids',
			]
	);


	if (empty($post_categories)) {
		return [];
	}


	foreach ($menu_items as $item) {


		if (
				$item->type === 'taxonomy'
				&&
				$item->object === 'category'
				&&
				in_array(
						(int)$item->object_id,
						$post_categories,
						true
				)
		) {

			return [
					[
							'title' => $item->title,
							'url' => get_term_link(
									(int)$item->object_id,
									'category'
							),
					],
			];

		}

	}


	return [];
}


/**
 * Хлебные крошки категории.
 */
function theme_get_category_breadcrumbs(): array
{
	return [
			[
					'title' => 'Главная',
					'url' => home_url('/'),
			],
			[
					'title' => single_cat_title('', false),
					'url' => '',
			],
	];
}


/**
 * Хлебные крошки поиска.
 */
function theme_get_search_breadcrumbs(): array
{
	return [
			[
					'title' => 'Главная',
					'url' => home_url('/'),
			],
			[
					'title' => 'Поиск',
					'url' => '',
			],
	];
}


/**
 * Хлебные крошки 404.
 */
function theme_get_404_breadcrumbs(): array
{
	return [
			[
					'title' => 'Главная',
					'url' => home_url('/'),
			],
			[
					'title' => '404',
					'url' => '',
			],
	];
}


/**
 * HTML вывод хлебных крошек.
 *
 * Классы не менять:
 *
 * bread-crumbs
 * bread-crumbs__list
 * bread-crumbs__item
 * bread-crumbs__link
 */
function theme_render_breadcrumbs(array $items): void
{
	?>
	<nav class="bread-crumbs">
		<ul class="bread-crumbs__list">

			<?php foreach ($items as $index => $item) : ?>

				<li class="bread-crumbs__item">

					<?php if (!empty($item['url'])) : ?>

						<a
								class="bread-crumbs__link link_underline"
								href="<?= esc_url($item['url']); ?>"
								aria-label="<?= esc_attr($item['title']); ?>"
						>
							<?= esc_html($item['title']); ?>
						</a>

					<?php else : ?>

						<span>
							<?= esc_html($item['title']); ?>
						</span>

					<?php endif; ?>

				</li>


				<?php if ($index < count($items) - 1) : ?>

					<li class="bread-crumbs__item">
						<i class="icon-angle-right _icon"></i>
					</li>

				<?php endif; ?>


			<?php endforeach; ?>

		</ul>
	</nav>
	<?php
}