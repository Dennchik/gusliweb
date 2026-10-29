<?php
	/**
	 * template-parts/front-page/tasks.php
	 * Выводит блок «Задача → решение → результат». Обёртку <section> даёт front-page.php.
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-tasks.php.
	 */

	$defaults = gusli_tasks_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$tasks = function_exists('get_field') ? get_field('tasks') : null;
	$tasks = is_array($tasks) ? $tasks : [];

	// Текст из поля или значение по умолчанию, если поле пустое.
	$tasks_text = static function (string $key) use ($tasks, $defaults): string {
		$value = trim((string)($tasks[$key] ?? ''));

		return $value !== '' ? $value : (string)$defaults[$key];
	};

	// Кейсы: строки повторителя с названием, иначе значения по умолчанию.
	$task_items = [];
	foreach ((array)($tasks['items'] ?? []) as $row) {
		if (empty($row['title'])) {
			continue;
		}

		$results = [];
		foreach ((array)($row['results'] ?? []) as $result) {
			if (!empty($result['value'])) {
				$results[] = ['value' => $result['value'], 'label' => $result['label'] ?? ''];
			}
		}

		$task_items[] = [
				'tag' => $row['tag'] ?? '',
				'title' => $row['title'],
				'task' => $row['task'] ?? '',
				'solution' => $row['solution'] ?? '',
				'results' => $results,
		];
	}
	$task_items = $task_items ?: $defaults['items'];

	$related_url = $tasks_text('related_url');
?>
<section class="main-content__section">
	<div class="section-tasks" id="tasks">
		<div class="section-tasks__body">
			<div class="section-tasks__container">
				<div class="section-tasks__sec-head">
					<div class="eye-brow"><?php echo esc_html($tasks_text('eyebrow')); ?></div>
					<h2><?php echo wp_kses_post($tasks_text('title')); ?></h2>
					<p><?php echo wp_kses_post($tasks_text('lead')); ?></p></div>
				<div class="section-tasks__items">
					<?php foreach ($task_items as $item) : ?>
						<article class="task"><span class="task__tag"><?php echo esc_html($item['tag']); ?></span>
							<h3><?php echo esc_html($item['title']); ?></h3>
							<dl>
								<dt>Задача</dt>
								<dd><?php echo wp_kses_post($item['task']); ?></dd>
								<dt>Решение</dt>
								<dd><?php echo wp_kses_post($item['solution']); ?></dd>
							</dl>
							<div class="task__res">
								<?php foreach ($item['results'] as $result) : ?>
									<div>
										<b><?php echo esc_html($result['value']); ?></b><span><?php echo esc_html($result['label']); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<div class="related-service">
					<div class="related-service__content">
						<div class="related-service__column">
							<span class="related-service__mono"><?php echo esc_html($tasks_text('related_mono')); ?></span>
							<h3><?php echo esc_html($tasks_text('related_title')); ?></h3>
							<p><?php echo wp_kses_post($tasks_text('related_text')); ?></p></div>
						<div class="related-service__column">
							<a class="button button--accent<?php echo str_starts_with($related_url, '#') ? ' anchor-link' : ''; ?>"
									href="<?php echo esc_url($related_url); ?>"
									target="_blank"
									rel="noopener"><span><?php echo esc_html($tasks_text('related_button')); ?></span></a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>