<?php
	/**
	 * template-parts/front-page/bundle.php
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-bundle.php.
	 * Скрипт сайта считает сумму по тексту цены и по data-price (см. комментарий в inc/acf-bundle.php).
	 */

	$defaults = gusli_bundle_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$bundle = function_exists('get_field') ? get_field('bundle') : null;
	$bundle = is_array($bundle) ? $bundle : [];

	// Текст из поля или значение по умолчанию, если поле пустое.
	$bundle_text = static function (array $source, string $key, string $default): string {
		$value = trim((string) ($source[$key] ?? ''));

		return $value !== '' ? $value : $default;
	};

	// Пакеты: берём строки повторителя с названием, иначе значения по умолчанию.
	$tiers = [];
	foreach ((array) ($bundle['tiers'] ?? []) as $row) {
		if (empty($row['name'])) {
			continue;
		}

		$features = [];
		foreach ((array) ($row['features'] ?? []) as $feature) {
			if (!empty($feature['text'])) {
				$features[] = ['text' => $feature['text'], 'excluded' => !empty($feature['excluded'])];
			}
		}

		$tiers[] = [
			'name'     => $row['name'],
			'who'      => $row['who'] ?? '',
			'price'    => $row['price'] ?? '',
			'note'     => $row['note'] ?? '',
			'meta'     => $row['meta'] ?? '',
			'hot'      => !empty($row['hot']),
			'features' => $features,
		];
	}
	$tiers = $tiers ?: $defaults['tiers'];

	// Дополнения: цена хранится числом и уходит в data-price.
	$addons = [];
	foreach ((array) ($bundle['addons'] ?? []) as $row) {
		if (!empty($row['name'])) {
			$addons[] = ['name' => $row['name'], 'hint' => $row['hint'] ?? '', 'price' => (int) ($row['price'] ?? 0)];
		}
	}
	$addons = $addons ?: $defaults['addons'];

	$hot_flag = $bundle_text($bundle, 'hot_flag', $defaults['hot_flag']);

	// Примечание можно скрыть галочкой в админке.
	$disclaimer = !empty($bundle['hide_disclaimer']) ? '' : $bundle_text($bundle, 'disclaimer', $defaults['disclaimer']);
?>
<section class="main-content__section">
	<div class="section-bundle" id="packages">
		<div class="section-bundle__body">
			<div class="section-bundle__container">
				<div class="section-bundle__header sec-head in">
					<div class="section-bundle__eye-brow"><?php echo esc_html($bundle_text($bundle, 'eyebrow', $defaults['eyebrow'])); ?><b><?php echo esc_html($bundle_text($bundle, 'eyebrow_bold', $defaults['eyebrow_bold'])); ?></b></div>
					<h2><?php echo wp_kses_post($bundle_text($bundle, 'title', $defaults['title'])); ?></h2>
					<p><?php echo wp_kses_post($bundle_text($bundle, 'lead', $defaults['lead'])); ?></p>
				</div>
				<div class="section-bundle__tiers tiers in">
					<?php foreach ($tiers as $tier) : ?>
						<article class="tier section-bundle__tier<?php echo $tier['hot'] ? ' tier--hot' : ''; ?>">
							<?php if ($tier['hot'] && $hot_flag !== '') : ?>
								<span class="tier__flag"><?php echo esc_html($hot_flag); ?></span>
							<?php endif; ?>
							<h3 class="tier__name"><?php echo esc_html($tier['name']); ?></h3>
							<p class="tier__who"><?php echo wp_kses_post($tier['who']); ?></p>
							<div class="tier__price"><b><?php echo esc_html($tier['price']); ?></b><span><?php echo esc_html($tier['note']); ?></span></div>
							<div class="tier__meta"><?php echo esc_html($tier['meta']); ?></div>
							<ul>
								<?php foreach ($tier['features'] as $feature) : ?>
									<li<?php echo $feature['excluded'] ? ' class="no"' : ''; ?>><?php echo esc_html($feature['text']); ?></li>
								<?php endforeach; ?>
							</ul>
							<a class="button <?php echo $tier['hot'] ? 'button--primary' : 'button--secondary'; ?>" data-pick="<?php echo esc_attr($tier['name']); ?>">Выбрать «<?php echo esc_html($tier['name']); ?>»</a>
						</article>
					<?php endforeach; ?>
				</div>
				<div class="addons section-bundle__addons">
					<div class="addons__head">
						<div><h3><?php echo esc_html($bundle_text($bundle, 'addons_title', $defaults['addons_title'])); ?></h3>
							<p><?php echo wp_kses_post($bundle_text($bundle, 'addons_lead', $defaults['addons_lead'])); ?></p>
						</div>
						<span><?php echo esc_html($bundle_text($bundle, 'base_label', $defaults['base_label'])); ?><span id="baseName"> Сайт</span> -<span id="basePrice">149 000 ₽</span></span>
					</div>
					<div class="addons__grid-wrapper">
						<div class="addons__grid">
							<?php foreach ($addons as $addon) : ?>
								<label class="addons__item addon"><input type="checkbox" data-price="<?php echo esc_attr((string) $addon['price']); ?>">
									<div><span><?php echo esc_html($addon['name']); ?></span><?php echo $addon['hint'] !== '' ? '<small> ' . esc_html($addon['hint']) . '</small>' : ''; ?></div>
									<b>+<?php echo esc_html(number_format($addon['price'], 0, ',', ' ')); ?> ₽</b></label>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="addons__foot"><span><?php echo esc_html($bundle_text($bundle, 'sum_label', $defaults['sum_label'])); ?></span><b id="sum">149 000 ₽</b></div>
				</div>
				<?php if ($disclaimer !== '') : ?>
					<p class="section-bundle__disclaimer"><?php echo wp_kses_post($disclaimer); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
