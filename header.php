<!DOCTYPE html>
<html class="scroll-smooth" <?php language_attributes(); ?>>
<?php
	//* На внутренних страницах якоря секций ведут на главную, «Контакты» — на страницу /contacts/, если она есть.
	$home_anchor = is_front_page() ? '' : home_url('/');
	$anchor_class = is_front_page() ? ' anchor-link' : '';
	$contacts_page = get_page_by_path('contacts');
	$version = wp_get_theme()->get('Version');
	$version = $version ?: time(); ?>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php // Если иконка сайта не задана в настройках WordPress и файла иконки нет, ссылку на неё не выводим. ?>
	<?php if (!has_site_icon() && file_exists(get_template_directory() . '/img/icons/gusli-logo.png')) : ?>
		<link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri());
		?>/img/icons/gusli-logo.png">
	<?php endif; ?>
	<?php
		/** Модули подключаются без параметра версии: бандл импортирует их по относительным путям, и разные URL дали
		 * бы двойную загрузку.
		 */
	?>
	<script type="module"
			crossorigin
			src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/vendors/layouts.js"></script>
	<script type="module"
			crossorigin
			src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/vendors/preloader.js"></script>
	<script type="module"
			crossorigin
			src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/vendors/vendor.js"></script>
	<script type="module"
			crossorigin
			src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/vendors/select.js"></script>
	<script type="module" crossorigin src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/main.js"></script>
	<script type="module" crossorigin src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/app.js"></script>
	<script type="module"
			crossorigin
			src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/vendors/modulepreload-polyfill.js"></script>
	<link rel="stylesheet" crossorigin href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/vendor.css">
	<link rel="stylesheet" crossorigin href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/main.css">
	<?php wp_head(); ?>

	<?php if (current_user_can('administrator')): ?>
		<style>
			#wpadminbar {
				position: fixed !important;
			}

			.page__header {
				margin-top: 30px;
			}

			@media screen and (max-width: 782px) {
				.page__header {
					margin-top: 46px;
				}
			}
		</style>
	<?php endif; ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="preloader">
	<div class="preloader-5"></div>
</div>
<div class="wrapper">
	<div class="page" id="top">
		<div class="bg" aria-hidden="true">
			<span class="bg__blob bg__blob--a"></span>
			<span class="bg__blob bg__blob--b"></span>
		</div>
		<div class="mobile-menu sidebar-menu">
			<div class="mobile-menu__body">
				<div class="mobile-menu__header">
					<div class="header__burger-button">
						<div class="burger-button">
							<svg viewBox="10 6 44 36">
								<path d="M19,15 L45,15 C70,15 58,-2 49.0177126,7 L19,37"></path>
								<path d="M19,24 L45,24 C61.2371586,24 57,49 41,33 L32,24"></path>
								<path d="M45,33 L19,33 C-8,33 6,-2 22,14 L45,37"></path>
							</svg>
						</div>
					</div>
				</div>
				<div class="mobile-menu__content bp-1"></div>
			</div>
		</div>
		<header class="header page__header offset-header">
			<div class="header__container container">
				<a class="header__logo logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="home"></a>
				<nav class="header__nav nav" data-da="bp-1" data-da-position="0" data-da-resolution="620">
					<ul class="nav__list">
						<li class="nav__item link-underline-center">
							<a class="nav__link<?php echo $anchor_class; ?>"
									href="<?php echo esc_url($home_anchor . '#fork'); ?>">Услуги</a>
						</li>
						<li class="nav__item link-underline-center">
							<a class="nav__link<?php echo $anchor_class; ?>"
									href="<?php echo esc_url($home_anchor . '#cases'); ?>">Кейсы</a>
						</li>
						<li class="nav__item link-underline-center">
							<a class="nav__link<?php echo $anchor_class; ?>"
									href="<?php echo esc_url($home_anchor . '#packages'); ?>">Пакеты</a>
						</li>
						<li class="nav__item link-underline-center">
							<a class="nav__link<?php echo $contacts_page ? '' : $anchor_class; ?>"
									href="<?php echo $contacts_page ? esc_url(get_permalink($contacts_page)) : '#contacts'; ?>">Контакты</a>
						</li>
					</ul>
				</nav>
				<div class="header__burger-button">
					<div class="burger-button">
						<svg viewBox="10 6 44 36">
							<path d="M19,15 L45,15 C70,15 58,-2 49.0177126,7 L19,37"></path>
							<path d="M19,24 L45,24 C61.2371586,24 57,49 41,33 L32,24"></path>
							<path d="M45,33 L19,33 C-8,33 6,-2 22,14 L45,37"></path>
						</svg>
					</div>
				</div>
				<a class="header__button button button--primary<?php echo $anchor_class; ?>"
						href="<?php echo esc_url($home_anchor . '#brief'); ?>">Оставить заявку</a>
			</div>

		</header>
		<main class="page__main-content">
			<div class="main-content" id="wrapper">
				<div class="main-content__content" id="content">