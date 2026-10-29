<?php
	/**
	 * Template Name: Контакты
	 * Шаблон страницы «Контакты» (подхватывается и автоматически для страницы со ссылкой /contacts/).
	 * Поля ACF: acf-json/group_gusli_contacts.json, значения по умолчанию: inc/acf-contacts.php.
	 * Блок «Связаться с нами» с формой выводит footer.php.
	 */

	$defaults = gusli_contacts_defaults();

	// Если плагин ACF выключен, страница показывает значения по умолчанию.
	$field = static function (string $key) use ($defaults): string {
		$value = function_exists('get_field') ? trim((string) get_field($key)) : '';

		return $value !== '' ? $value : (string) $defaults[$key];
	};

	// Факты: строки повторителя, иначе значения по умолчанию.
	$facts = [];
	foreach ((array) (function_exists('get_field') ? get_field('facts') : []) as $row) {
		if (!empty($row['text'])) {
			$facts[] = $row['text'];
		}
	}
	$facts = $facts ?: $defaults['facts'];

	// Абзацы: пустая строка внутри поля начинает новый абзац, перенос строки остаётся переносом.
	$paragraphs = static function (string $text): array {
		return array_filter(array_map('trim', preg_split('/\R{2,}/', $text)));
	};

	$map_src = esc_url($field('map_src'));
?>
<?php get_header(); ?>

<section class="main-content__section top">
	<section class="page-header">
		<div class="page-header__container container">
			<nav class="page-header__breadcrumbs" aria-label="Хлебные крошки">
				<a class="page-header__breadcrumb-link" href="<?php echo esc_url(home_url('/')); ?>">Главная</a><span class="page-header__breadcrumb-sep">/</span><span class="page-header__breadcrumb-current"><?php echo esc_html($field('title')); ?></span>
			</nav>
			<h1 class="page-header__title"><?php echo esc_html($field('title')); ?></h1>
			<p class="page-header__text"><?php echo esc_html($field('lead')); ?></p>
		</div>
	</section>
</section>
<section class="main-content__section">
	<div class="section-contacts">
		<div class="section-contacts__body">
			<div class="section-contacts__container">
				<div class="section-contacts__grid">
					<div class="section-contacts__info">
						<h2 class="section-contacts__title"><?php echo esc_html($field('info_title')); ?></h2>
						<?php foreach ([$field('text_1'), $field('text_2')] as $text) : ?>
							<?php foreach ($paragraphs($text) as $paragraph) : ?>
								<p class="section-contacts__text"><?php echo nl2br(esc_html($paragraph)); ?></p>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<ul class="section-contacts__facts">
							<?php foreach ($facts as $fact) : ?>
								<li><?php echo esc_html($fact); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php if ($map_src !== '') : ?>
						<div class="section-contacts__map">
							<iframe src="<?php echo $map_src; ?>"
									title="<?php echo esc_attr($field('map_title')); ?>"
									loading="lazy"
									allowfullscreen=""></iframe>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
