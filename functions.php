<?php

	declare(strict_types=1);

	/**
	 * functions.php
	 */

	function about_right()
	{
		return 	get_field('about_right');
	}

//* Подключаем иконки - '/inc/theme-icons.php'
	require_once get_template_directory() . '/inc/theme-icons.php';


//* Работа с изображениями - '/inc/images.php'
	require_once get_template_directory() . '/inc/images.php';


//* Календарь - '/inc/calendar.php'
//require_once get_template_directory() . '/inc/calendar.php';


//* Вспомогательные функции - '/inc/helpers.php'
	require_once get_template_directory() . '/inc/helpers.php';


//* Подключаем хлебные крошки - '/inc/breadcrumbs.php'
	require_once get_template_directory() . '/inc/breadcrumbs.php';


//* Подключение Walker для бокового меню - '/inc/class-aside-menu-walker.php'
	require_once get_template_directory() . '/inc/class-aside-menu-walker.php';


//* Подключение Walker для Главного меню шапки - '/inc/class-header-menu-walker.php'
	require_once get_template_directory() . '/inc/class-header-menu-walker.php';


//* Подключение Вывод колонки меню футера - '/inc/footer-menus.php
//require_once get_template_directory() . '/inc/footer-menus.php';

//** Отправка заявки с формы через AJAX.  Комментарии кода на русском языке.
	require_once get_template_directory() . '/inc/php-mailer.php';


//* Поля ACF первой секции главной - '/inc/acf-hero.php'
	require_once get_template_directory() . '/inc/acf-hero.php';
	require_once get_template_directory() . '/inc/acf-reasons.php';
	require_once get_template_directory() . '/inc/acf-fork.php';
	require_once get_template_directory() . '/inc/acf-bundle.php';
	require_once get_template_directory() . '/inc/acf-custom.php';
	require_once get_template_directory() . '/inc/acf-cases.php';
	require_once get_template_directory() . '/inc/acf-process.php';
	require_once get_template_directory() . '/inc/acf-faq.php';
	require_once get_template_directory() . '/inc/acf-tasks.php';
	require_once get_template_directory() . '/inc/acf-brief.php';
	require_once get_template_directory() . '/inc/acf-contacts.php';
	require_once get_template_directory() . '/inc/acf-cases-page.php';
	require_once get_template_directory() . '/inc/acf-case-item.php';

//* Адреса юридических страниц для подвала - '/inc/legal.php'
	require_once get_template_directory() . '/inc/legal.php';


//* Регистрация меню:
	function theme_register_menus(): void
	{
		register_nav_menus([
				'aside_menu' => 'Боковое меню страницы',
				'news_menu' => 'Боковое меню новостей',
				'header_menu' => 'Главное меню шапки',
		]);
	}

	add_action('after_setup_theme', 'theme_register_menus');


//* Настройка темы:
	function my_theme_setup(): void
	{
		add_theme_support('post-thumbnails');
		add_theme_support('title-tag');
	}

	add_action('after_setup_theme', 'my_theme_setup');


//* Скрипт отправки форм (AJAX) и данные для него:
	function theme_enqueue_forms(): void
	{
		$file = get_template_directory() . '/assets/forms.js';

		wp_enqueue_script(
			'gusli-forms',
			get_template_directory_uri() . '/assets/forms.js',
			[],
			(string) filemtime($file),
			true
		);

		wp_localize_script('gusli-forms', 'gusliForms', [
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'nonce'   => wp_create_nonce('gusli_form'),
		]);
	}

	add_action('wp_enqueue_scripts', 'theme_enqueue_forms');