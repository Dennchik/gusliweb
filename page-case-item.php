<?php
	/**
	 * Template Name: Кейс (страница проекта)
	 * Шаблон страницы одного кейса. Страница создаётся дочерней к странице «Кейсы».
	 * Поля ACF: acf-json/group_gusli_case_item.json, значения по умолчанию: inc/acf-case-item.php.
	 * Блок, у которого поле пустое и нет значения по умолчанию, не выводится.
	 */

	$page_id = get_queried_object_id();
	$defaults = gusli_case_item_defaults((string)get_post_field('post_name', $page_id));
	$acf_on = function_exists('get_field');
	$img_uri = get_template_directory_uri() . '/img/materials/';

	// Текст из поля или значение по умолчанию.
	$text = static function (string $key) use ($page_id, $defaults, $acf_on): string {
		$value = $acf_on ? trim((string)get_field($key, $page_id)) : '';

		return $value !== '' ? $value : (string)($defaults[$key] ?? '');
	};

	// Строки повторителя: из поля, а если оно пустое, значения по умолчанию.
	$rows = static function (string $key, string $required) use ($page_id, $defaults, $acf_on): array {
		$rows = [];
		foreach ((array)($acf_on ? get_field($key, $page_id) : []) as $row) {
			if (!empty($row[$required])) {
				$rows[] = $row;
			}
		}

		return $rows ?: (array)($defaults[$key] ?? []);
	};

	// Картинка: число (id из медиатеки) или путь по умолчанию без расширения (или с .webp).
	$picture = static function ($image, string $alt, bool $eager = false) use ($img_uri): string {
		if (is_numeric($image) && (int)$image > 0) {
			return wp_get_attachment_image((int)$image, 'large', false, ['alt' => $alt, 'loading' => $eager ? 'eager' : 'lazy']);
		}

		$image = (string)$image;
		if ($image === '') {
			return '';
		}

		if (substr($image, -5) === '.webp') {
			return '<img src="' . esc_url($img_uri . $image) . '" alt="' . esc_attr($alt) . '" width="40" height="40">';
		}

		return '<picture><source srcset="' . esc_url($img_uri . $image . '.webp') . '" type="image/webp">'
				. '<img src="' . esc_url($img_uri . $image . '.png') . '" alt="' . esc_attr($alt) . '" loading="' . ($eager ? 'eager' : 'lazy') . '" decoding="async"></picture>';
	};

	// Окно браузера со скриншотом.
	$frame = static function ($image, string $alt, string $caption, bool $eager = false) use ($picture): void {
		if (!$image) {
			return;
		}
		?>
		<figure class="section-case__shot">
			<div class="section-case__frame">
				<div class="section-case__frame-bar" aria-hidden="true"><i></i><i></i><i></i></div>
				<div class="section-case__frame-body"><?php echo $picture($image, $alt, $eager); ?></div>
			</div>
			<?php if ($caption !== '') : ?>
				<figcaption class="section-case__caption"><?php echo esc_html($caption); ?></figcaption><?php endif; ?>
		</figure>
		<?php
	};

	$title = $text('title') ?: get_the_title($page_id);
	$crumb = $text('crumb') ?: $title;
	$live_url = $text('live_url');
	$live_text = $text('live_text') ?: 'Открыть сайт';
	$cta_text = $text('cta_text') ?: 'Заказать такой же сайт';
	$brief_url = home_url('/#brief');
	$parent_id = (int)wp_get_post_parent_id($page_id);
	$list_url = $parent_id ? get_permalink($parent_id) : home_url('/cases/');

	$client_name = $text('client_name');
	$client_logo = ($acf_on ? get_field('client_logo', $page_id) : '') ?: ($defaults['client_logo'] ?? '');
	$meta = array_filter([
			'Тип проекта' => $text('type'),
			'Год' => $text('year'),
			'Стек' => $text('stack'),
	]);

	$hero_image = ($acf_on ? get_field('hero_image', $page_id) : '') ?: ($defaults['hero_image'] ?? '');
	$tasks = $rows('tasks', 'title');
	$steps = $rows('steps', 'name');
	$shots = $rows('shots', 'image');

	// Результаты: в ACF строки с полем text, по умолчанию простой список строк.
	$results = [];
	foreach ((array)($acf_on ? get_field('results', $page_id) : []) as $row) {
		if (!empty($row['text'])) {
			$results[] = $row['text'];
		}
	}
	$results = $results ?: (array)($defaults['results'] ?? []);
?>
<?php get_header(); ?>

<section class="main-content__section top">
	<article class="section-case">
		<div class="section-case__container container">
			<header class="section-case__hero">
				<nav class="section-case__crumbs" aria-label="Хлебные крошки">
					<a class="section-case__crumb" href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
					<span class="section-case__crumb-sep">/</span>
					<a class="section-case__crumb" href="<?php echo esc_url($list_url); ?>">Кейсы</a>
					<span class="section-case__crumb-sep">/</span>
					<span class="section-case__crumb-current"><?php echo esc_html($crumb); ?></span>
				</nav>
				<?php if ($text('eyebrow') !== '') : ?>
					<p class="section-case__eyebrow"><?php echo esc_html($text('eyebrow')); ?></p>
				<?php endif; ?>
				<h1 class="section-case__title"><?php echo esc_html($title); ?></h1>
				<?php if ($text('lead') !== '') : ?>
					<p class="section-case__lead"><?php echo esc_html($text('lead')); ?></p>
				<?php endif; ?>
				<div class="section-case__actions">
					<a class="button button--primary" href="<?php echo esc_url($brief_url); ?>">
						<span><?php echo esc_html($cta_text); ?></span>
					</a>
					<?php if ($live_url !== '') : ?>
						<a class="section-case__live" href="<?php echo esc_url($live_url); ?>" target="_blank"
								rel="noopener"><?php echo esc_html($live_text); ?>
							<span aria-hidden="true">↗</span>
						</a>
					<?php endif; ?>
				</div>
				<?php if ($client_name !== '' || $meta) : ?>
					<dl class="section-case__meta">
						<?php if ($client_name !== '') : ?>
							<div class="section-case__meta-item">
								<dt>Клиент</dt>
								<dd class="section-case__client">
									<?php if ($client_logo) : ?>
										<?php echo str_replace('<img ', '<img class="section-case__logo" ', $picture($client_logo, 'Логотип: ' . $client_name)); ?>
									<?php endif; ?>
									<span><?php echo esc_html($client_name); ?></span>
								</dd>
							</div>
						<?php endif; ?>
						<?php foreach ($meta as $label => $value) : ?>
							<div class="section-case__meta-item">
								<dt><?php echo esc_html($label); ?></dt>
								<dd><?php echo esc_html($value); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>
				<?php endif; ?>
				<?php $frame($hero_image, $text('hero_alt') ?: $title, $text('hero_caption'), true); ?>
			</header>

			<?php if ($tasks) : ?>
				<section class="section-case__section" aria-labelledby="case-task">
					<h2 class="section-case__h2" id="case-task"><?php echo esc_html($text('task_title') ?: 'Задача'); ?></h2>
					<div class="section-case__split">
						<?php foreach ($tasks as $task) : ?>
							<div class="section-case__card">
								<h3 class="section-case__h3"><?php echo esc_html($task['title']); ?></h3>
								<p class="section-case__text"><?php echo esc_html($task['text'] ?? ''); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if ($steps) : ?>
				<section class="section-case__section" aria-labelledby="case-work">
					<h2 class="section-case__h2"
							id="case-work"><?php echo esc_html($text('steps_title') ?: 'Что сделали'); ?></h2>
					<ol class="section-case__steps">
						<?php foreach ($steps as $step) : ?>
							<li class="section-case__step">
								<h3 class="section-case__step-name"><?php echo esc_html($step['name']); ?></h3>
								<p class="section-case__text"><?php echo esc_html($step['text'] ?? ''); ?></p>
							</li>
						<?php endforeach; ?>
					</ol>
				</section>
			<?php endif; ?>

			<?php if ($shots) : ?>
				<section class="section-case__section" aria-labelledby="case-shots">
					<h2 class="section-case__h2"
							id="case-shots"><?php echo esc_html($text('shots_title') ?: 'Как выглядит сайт'); ?></h2>
					<div class="section-case__gallery">
						<?php foreach ($shots as $shot) : ?>
							<?php $frame($shot['image'], (string)($shot['alt'] ?? '') ?: (string)($shot['caption'] ?? '') ?: $title, (string)($shot['caption'] ?? '')); ?>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endif; ?>

			<?php if ($results) : ?>
				<section class="section-case__section" aria-labelledby="case-results">
					<h2 class="section-case__h2"
							id="case-results"><?php echo esc_html($text('results_title') ?: 'Результат'); ?></h2>
					<ul class="section-case__outcomes">
						<?php foreach ($results as $result) : ?>
							<li class="section-case__outcome"><?php echo esc_html($result); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<section class="section-case__cta" aria-labelledby="case-cta">
				<h2 class="section-case__cta-title" id="case-cta">
					<?php echo esc_html($text('cta_title') ?: 'Нужен такой же сайт?'); ?>
				</h2>
				<?php if ($text('cta_lead') !== '') : ?>
					<p class="section-case__cta-text"><?php echo esc_html($text('cta_lead')); ?></p><?php endif; ?>
				<div class="section-case__actions">
					<a class="button button--primary"
							href="<?php echo esc_url($brief_url); ?>"><span><?php echo esc_html($cta_text); ?></span></a>
					<?php if ($live_url !== '') : ?>
						<a class="section-case__live section-case__live--light"
								href="<?php echo esc_url($live_url); ?>"
								target="_blank"
								rel="noopener"><?php echo esc_html($live_text); ?><span aria-hidden="true">↗</span></a>
					<?php endif; ?>
				</div>
			</section>

			<p class="section-case__back">
				<a class="section-case__back-link" href="<?php echo esc_url($list_url); ?>">← Все кейсы</a>
			</p>
		</div>
	</article>
</section>

<?php get_footer(); ?>
