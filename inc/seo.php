<?php

	declare(strict_types=1);

	/**
	 * inc/seo.php
	 *
	 * Лёгкое SEO без плагинов: title, description, canonical, robots,
	 * Open Graph / Twitter Card и структурированные данные (JSON-LD Schema.org).
	 *
	 * Всё подключается в <head> через wp_head. Ничего в шаблонах менять не нужно.
	 *
	 * Данные организации для Schema.org можно переопределить фильтром
	 * `gusli_seo_organization` (см. gusli_seo_organization()).
	 */

	/* -------------------------------------------------------------------------
	 * Название сайта и вспомогательные функции
	 * ---------------------------------------------------------------------- */

	/**
	 * Чистый заголовок: в теме заголовки страниц могут содержать разделитель
	 * «||» (см. template-parts/template-page.php) — он делит строку на строки
	 * при выводе. Для <title> и Schema берём обычный текст с пробелом.
	 */
	function gusli_seo_clean_title(string $title): string
	{
		return trim((string) preg_replace('/\s*\|\|\s*/u', ' ', $title));
	}


	/**
	 * Значение SEO-поля ACF для текущей записи (если ACF активен и поле задано).
	 * Поддерживаются поля: seo_title, seo_description.
	 */
	function gusli_seo_field(string $name): string
	{
		if (!function_exists('get_field')) {
			return '';
		}

		$value = get_field($name, get_queried_object_id());

		return is_string($value) ? trim($value) : '';
	}


	/**
	 * Логотип для Schema.org: кастомный логотип из настроек WP, иначе файл темы.
	 */
	function gusli_seo_logo(): string
	{
		if (has_custom_logo()) {
			$id = (int) get_theme_mod('custom_logo');

			$src = $id ? wp_get_attachment_image_url($id, 'full') : '';

			if ($src) {
				return $src;
			}
		}

		$theme_logo = get_template_directory() . '/img/icons/gusli-logo.png';

		if (file_exists($theme_logo)) {
			return get_template_directory_uri() . '/img/icons/gusli-logo.png';
		}

		return '';
	}


	/* -------------------------------------------------------------------------
	 * <title>
	 * ---------------------------------------------------------------------- */

	/**
	 * Персональный title из ACF-поля seo_title. Если поля нет — оставляем
	 * стандартный формат WordPress (Заголовок — Название сайта).
	 */
	add_filter('document_title_parts', 'gusli_seo_document_title_parts', 20);

	function gusli_seo_document_title_parts(array $parts): array
	{
		$override = gusli_seo_field('seo_title');

		if ($override !== '') {
			// Пользователь задал title целиком — выводим его как есть.
			return ['title' => $override];
		}

		// Убираем «||» из обычного заголовка страницы.
		if (!empty($parts['title'])) {
			$parts['title'] = gusli_seo_clean_title((string) $parts['title']);
		}

		return $parts;
	}

	add_filter('document_title_separator', 'gusli_seo_title_separator');

	function gusli_seo_title_separator(string $separator): string
	{
		return '—';
	}


	/* -------------------------------------------------------------------------
	 * meta description
	 * ---------------------------------------------------------------------- */

	function gusli_seo_description(): string
	{
		$description = '';

		if (is_singular() || is_page()) {
			// 1. ACF-поле seo_description.
			$description = gusli_seo_field('seo_description');

			if ($description === '') {
				$post = get_post(get_queried_object_id());

				if ($post) {
					// 2. Отрывок или начало контента.
					$description = $post->post_excerpt !== ''
							? $post->post_excerpt
							: (string) $post->post_content;
				}
			}
		} elseif (is_front_page()) {
			// Описание первой секции главной (ACF) или описание сайта.
			$defaults = function_exists('gusli_hero_defaults') ? gusli_hero_defaults() : [];
			$hero     = function_exists('get_field') ? get_field('hero') : null;

			$description = is_array($hero) && !empty($hero['description'])
					? (string) $hero['description']
					: (string) ($defaults['description'] ?? '');

			if ($description === '') {
				$description = get_bloginfo('description', 'display');
			}
		} elseif (is_category() || is_tag() || is_tax()) {
			$term        = get_queried_object();
			$description = ($term instanceof WP_Term) ? (string) term_description($term) : '';
		}

		// Очистка: без HTML, короткие пробелы, без неразрывных пробелов.
		$description = wp_strip_all_tags($description);
		$description = html_entity_decode($description, ENT_QUOTES, 'UTF-8');
		$description = str_replace(["\u{00A0}", '&nbsp;'], ' ', $description);
		$description = trim((string) preg_replace('/\s+/u', ' ', $description));

		// Стандартный лимит сниппета — до 160 символов.
		if (function_exists('mb_substr')) {
			return mb_substr($description, 0, 160);
		}

		return substr($description, 0, 160);
	}


	/* -------------------------------------------------------------------------
	 * canonical
	 * ---------------------------------------------------------------------- */

	function gusli_seo_canonical(): string
	{
		if (is_singular()) {
			return (string) get_permalink();
		}

		if (is_front_page() || is_home()) {
			return home_url('/');
		}

		if (is_category() || is_tag() || is_tax()) {
			$term = get_queried_object();

			if ($term instanceof WP_Term) {
				$link = get_term_link($term);

				if (!is_wp_error($link)) {
					return $link;
				}
			}
		}

		if (is_post_type_archive()) {
			$post_type = get_query_var('post_type');

			if (is_array($post_type)) {
				$post_type = reset($post_type);
			}

			$link = get_post_type_archive_link((string) $post_type);

			if ($link) {
				return $link;
			}
		}

		if (is_paged()) {
			return get_pagenum_link(1, false);
		}

		return '';
	}


	/* -------------------------------------------------------------------------
	 * robots
	 * ---------------------------------------------------------------------- */

	/**
	 * Директивы robots через штатный механизм WordPress (фильтр wp_robots).
	 * WordPress сам выводит <meta name="robots">, поэтому дубль не создаём.
	 */
	add_filter('wp_robots', 'gusli_seo_robots');

	function gusli_seo_robots(array $robots): array
	{
		// Поиск, 404 и страницы пагинации (со 2-й) не индексируем.
		if (is_search() || is_404() || is_paged()) {
			$robots['noindex'] = true;
			$robots['follow']  = true;

			return $robots;
		}

		$robots['index']             = true;
		$robots['follow']            = true;
		$robots['max-image-preview'] = 'large';
		$robots['max-snippet']       = '-1';

		return $robots;
	}


	/* -------------------------------------------------------------------------
	 * Open Graph / Twitter Card
	 * ---------------------------------------------------------------------- */

	/**
	 * Изображение для соцсетей: миниатюра записи, иначе файл темы, иначе иконка сайта.
	 */
	function gusli_seo_og_image(): string
	{
		if (is_singular() && has_post_thumbnail()) {
			$image = get_the_post_thumbnail_url(null, 'full');

			if ($image) {
				return $image;
			}
		}

		$fallback = get_template_directory() . '/img/hero-mockup.png';

		if (file_exists($fallback)) {
			return get_template_directory_uri() . '/img/hero-mockup.png';
		}

		if (has_site_icon()) {
			return (string) get_site_icon_url(512);
		}

		return '';
	}


	function gusli_seo_meta_tags(): void
	{
		$title       = wp_get_document_title();
		$description = gusli_seo_description();
		$canonical   = gusli_seo_canonical();
		$image       = gusli_seo_og_image();
		$site_name   = get_bloginfo('name', 'display');
		$og_type     = is_singular('post') ? 'article' : 'website';

		if ($description !== '') {
			echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
		}

		if ($canonical !== '') {
			echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
		}

		echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
		echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
		echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
		echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";

		if ($canonical !== '') {
			echo '<meta property="og:url" content="' . esc_url($canonical) . '">' . "\n";
		}

		if ($image !== '') {
			echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
		}

		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";

		if ($image !== '') {
			echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
		}
	}


	/* -------------------------------------------------------------------------
	 * JSON-LD Schema.org
	 * ---------------------------------------------------------------------- */

	/**
	 * Данные организации. Переопределяются фильтром gusli_seo_organization.
	 * Адрес и телефон добавьте, когда они будут известны (см. контакты в footer.php).
	 */
	function gusli_seo_organization(): array
	{
		$url  = home_url('/');
		$logo = gusli_seo_logo();

		$data = [
				'@context' => 'https://schema.org',
				'@type'    => 'ProfessionalService',
				'@id'      => $url . '#organization',
				'name'     => get_bloginfo('name', 'display'),
				'url'      => $url,
				'email'    => 'studio@obninsk-gusli.ru',
				'sameAs'   => [
						'https://t.me/gusli_studio',
				],
		];

		if ($logo !== '') {
			$data['logo'] = [
					'@type' => 'ImageObject',
					'url'   => $logo,
			];
		}

		return apply_filters('gusli_seo_organization', $data);
	}


	function gusli_seo_schema_website(): array
	{
		$url = home_url('/');

		return [
				'@context'      => 'https://schema.org',
				'@type'         => 'WebSite',
				'@id'           => $url . '#website',
				'url'           => $url,
				'name'          => get_bloginfo('name', 'display'),
				'inLanguage'    => get_bloginfo('language'),
				'potentialAction' => [
						'@type'       => 'SearchAction',
						'target'      => $url . '?s={search_term_string}',
						'query-input' => 'required name=search_term_string',
				],
		];
	}


	/**
	 * Пункты хлебных крошек в виде массива (переиспользуем логику inc/breadcrumbs.php).
	 */
	function gusli_seo_breadcrumb_items(): array
	{
		// На главной, в поиске и на 404 разметку хлебных крошек не выводим.
		if (is_front_page() || is_search() || is_404()) {
			return [];
		}

		if (is_page() && function_exists('theme_get_page_breadcrumbs')) {
			return theme_get_page_breadcrumbs();
		}

		if (is_single() && function_exists('theme_get_news_breadcrumbs')) {
			return theme_get_news_breadcrumbs();
		}

		if (is_category() && function_exists('theme_get_category_breadcrumbs')) {
			return theme_get_category_breadcrumbs();
		}

		if (is_search() && function_exists('theme_get_search_breadcrumbs')) {
			return theme_get_search_breadcrumbs();
		}

		if (is_404() && function_exists('theme_get_404_breadcrumbs')) {
			return theme_get_404_breadcrumbs();
		}

		return [];
	}


	function gusli_seo_schema_breadcrumb(): array
	{
		$items = gusli_seo_breadcrumb_items();

		if (empty($items)) {
			return [];
		}

		$canonical = gusli_seo_canonical();
		$list      = [];

		foreach ($items as $index => $item) {
			$list[] = [
					'@type'    => 'ListItem',
					'position' => $index + 1,
					'name'     => $item['title'],
					'item'     => !empty($item['url']) ? $item['url'] : $canonical,
			];
		}

		return [
				'@context'        => 'https://schema.org',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $list,
		];
	}


	/**
	 * Вопросы/ответы секции FAQ главной. Логика повторяет
	 * template-parts/front-page/faq-section.php, чтобы seo.php оставался автономным.
	 */
	function gusli_seo_faq_items(): array
	{
		$defaults = function_exists('gusli_faq_defaults') ? gusli_faq_defaults() : [];
		$faq      = function_exists('get_field') ? get_field('faq') : null;
		$faq      = is_array($faq) ? $faq : [];

		$items = [];

		foreach ((array) ($faq['items'] ?? []) as $row) {
			if (!empty($row['question']) && !empty($row['answer'])) {
				$items[] = [
						'question' => (string) $row['question'],
						'answer'   => (string) $row['answer'],
				];
			}
		}

		return $items ?: (array) ($defaults['items'] ?? []);
	}


	function gusli_seo_schema_faq(): array
	{
		$items = gusli_seo_faq_items();

		if (empty($items)) {
			return [];
		}

		$entities = [];

		foreach ($items as $item) {
			$entities[] = [
					'@type'          => 'Question',
					'name'           => $item['question'],
					'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags((string) $item['answer']),
					],
			];
		}

		return [
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => $entities,
		];
	}


	function gusli_seo_schema_article(): array
	{
		if (!is_singular('post')) {
			return [];
		}

		$post = get_post();

		if (!$post) {
			return [];
		}

		$publisher = gusli_seo_organization();

		$data = [
				'@context'        => 'https://schema.org',
				'@type'           => 'Article',
				'headline'        => gusli_seo_clean_title(get_the_title($post)),
				'datePublished'   => get_the_date('c', $post),
				'dateModified'    => get_the_modified_date('c', $post),
				'mainEntityOfPage' => get_permalink($post),
				'author'          => [
						'@type' => 'Person',
						'name'  => get_the_author_meta('display_name', (int) $post->post_author),
				],
				'publisher'       => [
						'@type' => $publisher['@type'] ?? 'Organization',
						'@id'   => $publisher['@id'] ?? (home_url('/') . '#organization'),
						'name'  => get_bloginfo('name', 'display'),
				],
		];

		$image = gusli_seo_og_image();

		if ($image !== '') {
			$data['image'] = $image;
		}

		return $data;
	}


	/**
	 * Вывод JSON-LD в <head>.
	 */
	function gusli_seo_schema(): void
	{
		$schemas = [
				gusli_seo_organization(),
				gusli_seo_schema_website(),
		];

		$breadcrumb = gusli_seo_schema_breadcrumb();

		if (!empty($breadcrumb)) {
			$schemas[] = $breadcrumb;
		}

		if (is_front_page()) {
			$faq = gusli_seo_schema_faq();

			if (!empty($faq)) {
				$schemas[] = $faq;
			}
		}

		if (is_singular('post')) {
			$article = gusli_seo_schema_article();

			if (!empty($article)) {
				$schemas[] = $article;
			}
		}

		foreach ($schemas as $schema) {
			$json = wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

			if ($json === false) {
				continue;
			}

			// Защита от преждевременного закрытия тега script.
			$json = str_replace('</', '<\/', $json);

			echo '<script type="application/ld+json">' . $json . '</script>' . "\n";
		}
	}


	/* -------------------------------------------------------------------------
	 * Подключение в <head>
	 * ---------------------------------------------------------------------- */

	add_action('wp_head', 'gusli_seo_meta_tags', 5);
	add_action('wp_head', 'gusli_seo_schema', 6);
