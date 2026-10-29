<?php
/* class-header-menu-walker.php */

declare(strict_types=1);

class Header_Menu_Walker extends Walker_Nav_Menu
{
	public function start_lvl(&$output, $depth = 0, $args = null): void
	{
		$output .= "\n<ul class=\"top-menu__sub-menu\">\n";
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

		$output .= '<li class="top-menu__item">';


		$link_class = 'top-menu__link';


		/**
		 * Проверяем активный пункт меню
		 */
		$is_current =
				in_array(
						'current-menu-item',
						$item->classes ?? [],
						true
				)
				||
				in_array(
						'current-menu-parent',
						$item->classes ?? [],
						true
				)
				||
				in_array(
						'current-menu-ancestor',
						$item->classes ?? [],
						true
				);


		/**
		 * Проверяем якорную ссылку
		 */
		$is_anchor = isset($item->url)
				&& str_starts_with($item->url, '#');


		if ($is_anchor) {
			$link_class .= ' anchor-link link_underline';
		}


		/**
		 * Активный пункт
		 */
		if ($is_current) {
			$link_class .= ' link-hover';
		}


		/**
		 * Атрибуты ссылки
		 */
		$attributes = '';


		if (!empty($item->attr_title)) {
			$attributes .= ' title="' . esc_attr($item->attr_title) . '"';
		}


		if ($is_current) {
			$attributes .= ' aria-current="page"';
		}


		$output .= '<a class="' . esc_attr($link_class) . '" href="' . esc_url($item->url) . '"' . $attributes . '>';


		$output .= '<span>';
		$output .= esc_html($item->title);
		$output .= '</span>';


		$output .= '</a>';
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