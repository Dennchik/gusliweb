<?php
	/**
	 * template-parts/front-page/faq-section.php
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-faq.php.
	 * Раскрытие вопросов делает скрипт сайта по атрибутам data-question и data-trigger.
	 * (Файл faq.php в этой папке остался от другого проекта.)
	 */

	$defaults = gusli_faq_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$faq = function_exists('get_field') ? get_field('faq') : null;
	$faq = is_array($faq) ? $faq : [];

	// Текст из поля или значение по умолчанию, если поле пустое.
	$faq_text = static function (string $key) use ($faq, $defaults): string {
		$value = trim((string) ($faq[$key] ?? ''));

		return $value !== '' ? $value : (string) $defaults[$key];
	};

	// Вопросы: строки повторителя с вопросом и ответом, иначе значения по умолчанию.
	$faq_items = [];
	foreach ((array) ($faq['items'] ?? []) as $row) {
		if (!empty($row['question']) && !empty($row['answer'])) {
			$faq_items[] = ['question' => $row['question'], 'answer' => $row['answer']];
		}
	}
	$faq_items = $faq_items ?: $defaults['items'];
?>
<section class="main-content__section">
	<div class="section-questions">
		<div class="section-questions__container">
			<div class="section-questions__body">
				<div class="section-questions__header sec-head in" data-rv="">
					<div class="section-questions__eye-brow"><?php echo esc_html($faq_text('eyebrow')); ?></div>
					<h2><?php echo wp_kses_post($faq_text('title')); ?></h2></div>
				<div class="section-questions__fag faq in" data-rv="">
					<?php foreach ($faq_items as $item) : ?>
						<div class="details" data-question>
							<summary data-trigger><?php echo esc_html($item['question']); ?></summary>
							<div class="details__wrapper">
								<div class="section-questions__text">
									<?php foreach (preg_split('/\R+/', $item['answer'], -1, PREG_SPLIT_NO_EMPTY) as $paragraph) : ?>
										<p><?php echo wp_kses_post($paragraph); ?></p>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
