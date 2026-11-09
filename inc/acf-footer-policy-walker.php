<?php

declare(strict_types=1);

/**
 * Walker для юридических ссылок в подвале.
 */
class Footer_Policy_Walker extends Walker_Nav_Menu
{
	public function start_el(
		&$output,
		$data_object,
		$depth = 0,
		$args = null,
		$current_object_id = 0
	): void {
		$item = $data_object;
		$item_title = isset($item->title) ? (string) $item->title : '';
		$item_url = isset($item->url) ? (string) $item->url : '';

		$classes = empty($item->classes) ? [] : (array) $item->classes;

		$is_current =
			in_array('current-menu-item', $classes, true)
			|| in_array('current-menu-parent', $classes, true)
			|| in_array('current-menu-ancestor', $classes, true)
			|| (
				isset($item->object_id)
				&& (int) $item->object_id === get_queried_object_id()
			);

		$output .= '<li class="footer__policy-item">';

		$attributes = ' class="footer__link" href="' . esc_url($item_url) . '"';

		if (!empty($item->attr_title)) {
			$attributes .= ' title="' . esc_attr($item->attr_title) . '"';
		}

		if ($is_current) {
			$attributes .= ' aria-current="page"';
		}

		$output .= '<a' . $attributes . '>';
		$output .= esc_html($item_title);
		$output .= '</a>';
	}

	public function end_el(
		&$output,
		$data_object,
		$depth = 0,
		$args = null
	): void {
		$output .= "</li>\n";
	}
}

/**
 * Резервный вывод юридических ссылок, если меню не назначено в WordPress.
 */
function gusli_footer_policy_fallback(): void
{
	$links = [
		'privacy-policy' => 'Политика обработки данных',
		'personal-data-consent' => 'Согласие на обработку данных',
		'advertising-consent' => 'Рекламное согласие',
		'cookie-policy' => 'Политика cookies',
	];

	foreach ($links as $slug => $title) {
		echo '<li class="footer__policy-item">';
		echo '<a class="footer__link" href="' . esc_url(gusli_legal_url($slug)) . '">';
		echo esc_html($title);
		echo '</a>';
		echo '</li>\n';
	}
}
