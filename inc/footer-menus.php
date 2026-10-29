<?php

declare(strict_types=1);

/**
 * inc/footer-menus.php
 */


//* Вывод колонки меню футера:
function footer_menu_column(string $field_name, $post_id): void
{
	if (!have_rows($field_name, $post_id)) {
		return;
	}

	while (have_rows($field_name, $post_id)) {

		the_row();

		$link = get_sub_field('ссылка');

		if (!$link) {
			continue;
		}

		$name = get_sub_field('имя_ссылки');

		if (!$name) {
			continue;
		}

		$url = $link['url'] ?? '#';
		$target = $link['target'] ?? '_self';
		?>

		<li class="navigation-footer__item-list">
			<a
					href="<?php echo esc_url($url); ?>"
					target="<?php echo esc_attr($target); ?>"
			>
				<?php echo esc_html($name); ?>
			</a>
		</li>

		<?php
	}
}