<?php
	/**
	 * template-parts/front-page/reasons.php
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-reasons.php.
	 */

	$defaults = gusli_reasons_defaults();

	// Поле ACF. Если плагин выключен, страница всё равно показывает значения по умолчанию.
	$reasons_field = static function (string $name) {
		$group = function_exists('get_field') ? get_field('reasons') : null;

		return is_array($group) ? ($group[$name] ?? null) : null;
	};

	// Текстовое поле ACF или значение по умолчанию, если поле пустое.
	$reasons_text = static function (string $name, string $default) use ($reasons_field): string {
		$value = trim((string) $reasons_field($name));

		return $value !== '' ? $value : $default;
	};

	$reasons_eyebrow = $reasons_text('eyebrow', $defaults['eyebrow']);
	$reasons_title   = $reasons_text('title', $defaults['title']);
	$reasons_lead    = $reasons_text('lead', $defaults['lead']);

	$reasons_items = [];
	foreach ((array) $reasons_field('items') as $row) {
		if (!empty($row['title'])) {
			$reasons_items[] = ['title' => $row['title'], 'text' => $row['text'] ?? ''];
		}
	}
	$reasons_items = $reasons_items ?: $defaults['items'];
?>
<section class="main-content__section">
	<div class="section-reasons">
		<div class="section-reasons__body">
			<div class="section-reasons__container">
				<div class="section-reasons__header sec-head in">
					<div class="section-reasons__eye-brow"><?php echo wp_kses_post($reasons_eyebrow); ?></div>
					<h2><?php echo wp_kses_post($reasons_title); ?></h2>
					<p><?php echo wp_kses_post($reasons_lead); ?></p>
				</div>
				<div class="section-reasons__pains pains in">
					<?php foreach ($reasons_items as $index => $item) : ?>
						<div class="section-reasons__pain"><span class="section-reasons__mono">ПРИЧИНА <?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
							<h3><?php echo wp_kses_post($item['title']); ?></h3>
							<p><?php echo wp_kses_post($item['text']); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
