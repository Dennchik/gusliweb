<?php
	/**
	 * template-parts/front-page/compare.php
	 * Выводит таблицу «Что входит в каждый формат». Обёртку <section> даёт front-page.php.
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-tasks.php.
	 */

	$defaults = gusli_compare_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$compare = function_exists('get_field') ? get_field('compare') : null;
	$compare = is_array($compare) ? $compare : [];

	// Текст из поля или значение по умолчанию, если поле пустое.
	$compare_text = static function (string $key) use ($compare, $defaults): string {
		$value = trim((string)($compare[$key] ?? ''));

		return $value !== '' ? $value : (string)$defaults[$key];
	};

	// Заголовки столбцов.
	$columns = [];
	foreach ((array)($compare['columns'] ?? []) as $row) {
		if (!empty($row['text'])) {
			$columns[] = $row['text'];
		}
	}
	$columns = $columns ?: $defaults['columns'];

	// Строки таблицы: название и ячейки с отметкой «да»/«нет».
	$rows = [];
	foreach ((array)($compare['rows'] ?? []) as $row) {
		if (empty($row['label'])) {
			continue;
		}

		$cells = [];
		foreach ((array)($row['cells'] ?? []) as $cell) {
			$cells[] = ['text' => (string)($cell['text'] ?? ''), 'mark' => (string)($cell['mark'] ?? '')];
		}

		$rows[] = ['label' => $row['label'], 'cells' => $cells];
	}
	$rows = $rows ?: $defaults['rows'];

	// Класс ячейки по отметке.
	$mark_class = static function (string $mark): string {
		return match ($mark) {
			'y' => ' class="section-compare__y"',
			'n' => ' class="section-compare__n"',
			default => '',
		};
	};
?>
<section class="main-content__section">
	<div class="section-compare">
		<div class="section-compare__body">
			<div class="section-compare__container">
				<div class="section-compare__sec-head sec-head in">
					<div class="section-compare__eye-brow"><?php echo esc_html($compare_text('eyebrow')); ?></div>
					<h2><?php echo wp_kses_post($compare_text('title')); ?></h2></div>
				<div class="section-compare__table-wrap">
					<table>
						<thead>
						<tr>
							<th scope="col"><?php echo esc_html($compare_text('param_label')); ?></th>
							<?php foreach ($columns as $column) : ?>
								<th scope="col"><?php echo esc_html($column); ?></th>
							<?php endforeach; ?>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($rows as $row) : ?>
							<tr>
								<th scope="row"><?php echo esc_html($row['label']); ?></th>
								<?php foreach ($row['cells'] as $cell) : ?>
									<td<?php echo $mark_class($cell['mark']); ?>><?php echo esc_html($cell['text']); ?></td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>