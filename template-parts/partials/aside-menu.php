<aside class="aside-menu"
       data-da="bp-1"
       data-da-position="1"
       data-da-resolution="690">

	<?php

	$menu_location = 'aside_menu';


	// Страницы из новостей
	if (theme_page_has_news_menu(get_the_ID())) {
		$menu_location = 'news_menu';
	}


	wp_nav_menu([
			'theme_location' => $menu_location,
			'container' => false,
			'menu_class' => 'aside-menu__list',
			'fallback_cb' => false,
			'walker' => new Aside_Menu_Walker(),
	]);

	?>

</aside>