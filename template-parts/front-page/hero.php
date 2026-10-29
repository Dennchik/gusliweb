<?php
	/**
	 * template-parts/front-page/hero.php

	 * Поля ACF описаны в inc/acf-hero.php, пустые поля заменяются значениями по умолчанию.
	 */

	$defaults = gusli_hero_defaults();

	// Поле ACF. Если плагин выключен, страница всё равно показывает значения по умолчанию.
	$hero_field = static function (string $name) {
		$group = function_exists('get_field') ? get_field('hero') : null;

		return is_array($group) ? ($group[$name] ?? null) : null;
	};

	// Текстовое поле ACF или значение по умолчанию, если поле пустое.
	$hero_text = static function (string $name, string $default) use ($hero_field): string {
		$value = trim((string) $hero_field($name));

		return $value !== '' ? $value : $default;
	};

	$hero_title        = $hero_text('title', $defaults['title']);
	$hero_title_accent = $hero_text('title_accent', $defaults['title_accent']);
	$hero_button_main  = $hero_text('button_primary', $defaults['button_primary']);
	$hero_button_text  = $hero_text('button_text', $defaults['button_text']);
	$hero_button_link  = $hero_text('button_link', $defaults['button_link']);
	$hero_note         = $hero_text('note', $defaults['note']);

	// Абзацы описания: каждая непустая строка — отдельный абзац.
	$hero_paragraphs = preg_split('/\R+/', $hero_text('description', $defaults['description']), -1, PREG_SPLIT_NO_EMPTY);

	// Плашки и цифры берём из повторителей, а если они пустые — значения по умолчанию.
	$hero_badges = [];
	foreach ((array) $hero_field('badges') as $row) {
		if (!empty($row['text'])) {
			$hero_badges[] = $row['text'];
		}
	}
	$hero_badges = $hero_badges ?: $defaults['badges'];

	$hero_stats = [];
	foreach ((array) $hero_field('stats') as $row) {
		if (!empty($row['value'])) {
			$hero_stats[] = ['value' => $row['value'], 'label' => $row['label'] ?? ''];
		}
	}
	$hero_stats = $hero_stats ?: $defaults['stats'];

	$hero_image_id = (int) $hero_field('image');
	$hero_link_cls = str_starts_with($hero_button_link, '#') ? ' anchor-link' : '';
?>
<section class="main-content__section top">
	<div class="hero">
		<div class="hero__container container">
			<div class="hero__content">
				<h1 class="hero__title">
					<span><?php echo wp_kses_post($hero_title); ?></span>
					<span class="hero__title-accent"><?php echo wp_kses_post($hero_title_accent); ?></span>
				</h1>
				<div class="hero__text">
					<?php foreach ($hero_paragraphs as $paragraph) : ?>
						<p class="hero__description"><?php echo wp_kses_post($paragraph); ?></p>
					<?php endforeach; ?>
				</div>
				<div class="hero__actions">
					<button class="button button--primary" data-modal-open><?php echo esc_html($hero_button_main); ?></button>
					<a class="button button--secondary<?php echo esc_attr($hero_link_cls); ?>" href="<?php echo esc_url($hero_button_link); ?>"><?php echo esc_html($hero_button_text); ?></a>
				</div>
				<ul class="hero__badges">
					<?php foreach ($hero_badges as $badge) : ?>
						<li class="hero__badge"><?php echo esc_html($badge); ?></li>
					<?php endforeach; ?>
				</ul>
				<p class="hero__note"><span class="hero__dot"></span><?php echo esc_html($hero_note); ?></p>
			</div>
			<div class="hero__preview">
				<?php if ($hero_image_id) : ?>
					<?php echo wp_get_attachment_image($hero_image_id, 'large', false, ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
				<?php else : ?>
					<picture>
						<source srcset="<?php echo esc_url(get_template_directory_uri()); ?>/img/hero-mockup.webp" type="image/webp">
						<img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/hero-mockup.png" alt="<?php echo esc_attr($defaults['image_alt']); ?>">
					</picture>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="section-strip">
		<div class="section-strip__body">
			<div class="section-strip__container">
				<?php foreach ($hero_stats as $stat) : ?>
					<div class="section-strip__stat stat"><b><?php echo esc_html($stat['value']); ?></b><span><?php echo esc_html($stat['label']); ?></span></div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
