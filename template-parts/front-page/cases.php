<?php
	/**
	 * template-parts/front-page/cases.php
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-cases.php.
	 */

	$defaults = gusli_cases_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$cases_group = function_exists('get_field') ? get_field('cases') : null;
	$cases_group = is_array($cases_group) ? $cases_group : [];

	// Текст из поля или значение по умолчанию, если поле пустое.
	$cases_text = static function (string $key) use ($cases_group, $defaults): string {
		$value = trim((string)($cases_group[$key] ?? ''));

		return $value !== '' ? $value : (string)$defaults[$key];
	};

	// Карточки: строки повторителя с названием, иначе значения по умолчанию.
	$cards = [];
	foreach ((array)($cases_group['items'] ?? []) as $row) {
		if (empty($row['title'])) {
			continue;
		}

		$tags = [];
		foreach ((array)($row['tags'] ?? []) as $tag) {
			if (!empty($tag['text'])) {
				$tags[] = ['text' => $tag['text'], 'accent' => !empty($tag['accent'])];
			}
		}

		$cards[] = [
				'title' => $row['title'],
				'alt' => $row['alt'] ?? '',
				'image_id' => (int)($row['image'] ?? 0),
				'image' => '',
				'link' => $row['link'] ?? '',
				'tags' => $tags,
		];
	}

	if (!$cards) {
		foreach ($defaults['cases'] as $case) {
			$cards[] = $case + ['image_id' => 0];
		}
	}
?>
<section class="main-content__section">
	<section class="cases" id="cases">
		<div class="cases__body">
			<div class="cases__container container">
				<div class="cases__header"><h2><?php echo esc_html($cases_text('title')); ?></h2>
					<a class="cases__link link-underline-center" href="<?php echo esc_url($cases_text('link_url')); ?>">
						<span><?php echo esc_html($cases_text('link_text')); ?></span>
					</a>
				</div>
				<div class="cases__grid">
					<?php foreach ($cards as $card) : ?>
						<?php $card_link = trim((string)$card['link']);
						// Без адреса карточка выводится без ссылки (div вместо a), оформление то же. ?>
						<article class="case-card"><?php if ($card_link !== '') : ?>
							<a class="case-card__link"
									href="<?php echo esc_url($card_link); ?>"
									<?php echo str_starts_with($card_link, home_url('/')) ? '' : 'target="_blank" rel="noopener"'; ?>>
								<?php else : ?>
								<div class="case-card__link"><?php endif; ?>
									<div class="case-card__image-wrapper">
										<?php if ($card['image_id']) : ?>
											<?php echo wp_get_attachment_image($card['image_id'], 'large', false, ['class' => 'case-card__image', 'alt' => $card['alt'] ?: $card['title']]); ?>
										<?php else : ?>
											<img class="case-card__image"
													src="<?php echo esc_url(get_template_directory_uri() . $card['image']); ?>"
													alt="<?php echo esc_attr($card['alt'] ?: $card['title']); ?>">
										<?php endif; ?>
									</div>
									<div class="case-card__info">
										<h3 class="case-card__title"><?php echo esc_html($card['title']); ?></h3>
										<div class="case-card__tags">
											<?php foreach ($card['tags'] as $tag) : ?>
												<span class="case-card__tag
												<?php echo $tag['accent'] ? ' tag--accent' : ''; ?>">
													<?php echo esc_html($tag['text']); ?>
												</span>
											<?php endforeach; ?>
										</div>
									</div>
									<?php echo $card_link !== '' ? '</a>' : '</div>'; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
</section>
