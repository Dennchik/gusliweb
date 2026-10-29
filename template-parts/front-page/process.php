<?php
	/**
	 * template-parts/front-page/process.php
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-process.php.
	 */

	$defaults = gusli_process_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$process = function_exists('get_field') ? get_field('process') : null;
	$process = is_array($process) ? $process : [];

	// Текст из поля или значение по умолчанию, если поле пустое.
	$process_text = static function (string $key) use ($process, $defaults): string {
		$value = trim((string) ($process[$key] ?? ''));

		return $value !== '' ? $value : (string) $defaults[$key];
	};

	// Шаги: строки повторителя с названием, иначе значения по умолчанию.
	$steps = [];
	foreach ((array) ($process['steps'] ?? []) as $row) {
		if (!empty($row['title'])) {
			$steps[] = ['title' => $row['title'], 'text' => $row['text'] ?? '', 'time' => $row['time'] ?? ''];
		}
	}
	$steps = $steps ?: $defaults['steps'];
?>
<section class="main-content__section">
	<div class="section-work">
		<div class="section-work__body">
			<div class="section-work__container">
				<div class="section-work__header sec-head in">
					<div class="eye-brow"><?php echo esc_html($process_text('eyebrow')); ?></div>
					<h2><?php echo wp_kses_post($process_text('title')); ?></h2>
					<p><?php echo wp_kses_post($process_text('lead')); ?></p></div>
				<div class="section-work__steps steps in">
					<?php foreach ($steps as $index => $step) : ?>
						<div class="step"><span class="step__n"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
							<h3><?php echo esc_html($step['title']); ?></h3>
							<p><?php echo wp_kses_post($step['text']); ?></p>
							<div class="step__t"><?php echo esc_html($step['time']); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
				<p class="you-need in" data-rv=""><span><b><?php echo esc_html($process_text('need_label')); ?></b> <?php echo wp_kses_post($process_text('need_text')); ?></span></p></div>
		</div>
	</div>
</section>
