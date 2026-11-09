<?php
/* class-header-menu-walker.php */

declare(strict_types=1);

class Header_Menu_Walker extends Walker_Nav_Menu
{
	public function start_lvl(&$output, $depth = 0, $args = null): void
	{
		$output .= "\n<ul class=\"nav__sub-menu\">\n";
	}

	public function end_lvl(&$output, $depth = 0, $args = null): void
	{
		$output .= "</ul>\n";
	}

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
			|| in_array('current_page_parent', $classes, true);

		$is_current_link =
			isset($item->object_id)
			&& (int) $item->object_id === get_queried_object_id();

		$is_anchor = $item_url !== '' && str_starts_with($item_url, '#');
		$is_parent = in_array('menu-item-has-children', $classes, true);

		// Якоря внутренних страниц должны вести на главную страницу.
		if ($is_anchor && !is_front_page()) {
			$item_url = home_url('/') . $item_url;
		}

		$item_class = 'nav__item link-underline-center';

		if ($is_parent) {
			$item_class .= ' nav__item--parent';
		}

		if ($is_current || $is_current_link) {
			$item_class .= ' is-active';
		}

		$output .= '<li class="' . esc_attr($item_class) . '">';

		$link_class = 'nav__link';

		if ($is_anchor) {
			$link_class .= ' anchor-link';
		}

		if ($is_current || $is_current_link) {
			$link_class .= ' link-hover';
		}

		$attributes = '';

		if (!empty($item->attr_title)) {
			$attributes .= ' title="' . esc_attr($item->attr_title) . '"';
		}

		if ($is_current || $is_current_link) {
			$attributes .= ' aria-current="page"';
		}

		$output .= '<a class="' . esc_attr($link_class) . '" href="' . esc_url($item_url) . '"' . $attributes . '>';
		$output .= '<span>' . esc_html($item_title) . '</span>';
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
