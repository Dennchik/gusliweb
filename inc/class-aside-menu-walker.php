<?php
/* class-aside-menu-walker.php */

declare(strict_types=1);

class Aside_Menu_Walker extends Walker_Nav_Menu
{
	public function start_lvl(&$output, $depth = 0, $args = null): void
	{
		if ($depth >= 1) {
			$output .= "\n<ul class=\"aside-menu__sub-menu _collapse\">\n";
		} else {
			$output .= "\n<ul class=\"aside-menu__sub-menu\">\n";
		}
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
	): void
	{
		$item = $data_object;
		$item_title = isset($item->title)
				? (string)$item->title
				: '';
		$item_url = isset($item->url)
				? (string)$item->url
				: '';
		$classes = empty($item->classes)
				? []
				: (array)$item->classes;

		$class_names = 'aside-menu__item';
		$is_current =
				in_array('current-menu-item', $classes, true)
				||
				in_array('current-menu-parent', $classes, true)
				||
				in_array('current-menu-ancestor', $classes, true)
				||
				in_array('current_page_parent', $classes, true);

		$is_current_link = (
				isset($item->object_id)
				&&
				(int)$item->object_id === get_queried_object_id()
		);

		$is_parent = (
				in_array('menu-item-has-children', $classes, true)
				&&
				$depth > 0
		);

		if ($is_parent) {
			$class_names .= ' _toggle-menu';

			if ($is_current) {
				$class_names .= ' is-active';
			}
		}

		$output .= '<li class="' . esc_attr($class_names) . '">';

		if ($is_parent) {

			$output .= '
				<div class="aside-menu__link _trigger">
					<span>'
					. esc_html($item_title) .
					'</span>
					<i class="icon-angle-down icon"></i>
				</div>
			';

		} else {

			$link_class = 'aside-menu__link';


			if ($is_current || $is_current_link) {
				$link_class .= ' link-hover';
			}

			$output .= '
				<a class="' . esc_attr($link_class) . '"
				   href="' . esc_url($item_url) . '">
					<span>'
					. esc_html($item_title) .
					'</span>
				</a>
			';
		}
	}

	public function end_el(
			&$output,
			$data_object,
			$depth = 0,
			$args = null
	): void
	{

		$output .= "</li>\n";
	}
}