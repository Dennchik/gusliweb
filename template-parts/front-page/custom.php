<?php
	/**
	 * template-parts/front-page/custom.php
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-custom.php.
	 */

	$defaults = gusli_custom_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$custom = function_exists('get_field') ? get_field('custom') : null;
	$custom = is_array($custom) ? $custom : [];

	// Текст из поля или значение по умолчанию, если поле пустое.
	$custom_text = static function (string $key) use ($custom, $defaults): string {
		$value = trim((string) ($custom[$key] ?? ''));

		return $value !== '' ? $value : (string) $defaults[$key];
	};

	// Условия кастома: строки повторителя с текстом, иначе значения по умолчанию.
	$criteria = [];
	foreach ((array) ($custom['crit_items'] ?? []) as $row) {
		if (!empty($row['text'])) {
			$criteria[] = $row['text'];
		}
	}
	$criteria = $criteria ?: $defaults['crit_items'];

	// Этапы: строки повторителя с названием, иначе значения по умолчанию.
	$phases = [];
	foreach ((array) ($custom['phases'] ?? []) as $row) {
		if (!empty($row['title'])) {
			$phases[] = [
				'title' => $row['title'],
				'text'  => $row['text'] ?? '',
				'price' => $row['price'] ?? '',
				'term'  => $row['term'] ?? '',
			];
		}
	}
	$phases = $phases ?: $defaults['phases'];

	$button_link = $custom_text('button_link');
	$button_class = 'button button--accent' . (str_starts_with($button_link, '#') ? ' anchor-link' : '');
?>
<section class="main-content__section">
	<div class="section-calculation light" id="custom">
		<div class="section-calculation__body">
			<div class="section-calculation__container">
				<div class="section-calculation__header sec-head in">
					<div class="section-calculation__eye-brow"><?php echo esc_html($custom_text('eyebrow')); ?><b><?php echo esc_html($custom_text('eyebrow_bold')); ?></b></div>
					<h2><?php echo wp_kses_post($custom_text('title')); ?></h2>
					<p><?php echo wp_kses_post($custom_text('lead')); ?></p>
				</div>
				<div class="section-calculation__custom-grid custom-grid in">
					<div class="section-calculation__crit"><h3><?php echo esc_html($custom_text('crit_title')); ?></h3>
						<ul>
							<?php foreach ($criteria as $item) : ?>
								<li><?php echo esc_html($item); ?></li>
							<?php endforeach; ?>
						</ul>
						<p><?php echo wp_kses_post($custom_text('crit_note')); ?></p></div>
					<div>
						<div class="section-calculation__phases">
							<?php foreach ($phases as $index => $phase) : ?>
								<div class="phase"><span class="phase__num">ЭТАП <?php echo esc_html((string) ($index + 1)); ?></span>
									<div class="phase__text"><h4><?php echo esc_html($phase['title']); ?></h4>
										<p><?php echo wp_kses_post($phase['text']); ?></p></div>
									<div class="phase__price"><span><?php echo esc_html($phase['price']); ?></span><small><?php echo esc_html($phase['term']); ?></small></div>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="section-calculation__footer"><a class="<?php echo esc_attr($button_class); ?>"
									href="<?php echo esc_url($button_link); ?>"><?php echo esc_html($custom_text('button_text')); ?></a><span><?php echo esc_html($custom_text('footer_note')); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
