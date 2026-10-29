<?php
	/**
	 * Template Name: Кейсы
	 * Шаблон страницы «Кейсы» (подхватывается и автоматически для страницы со ссылкой /cases/).
	 * Поля ACF: acf-json/group_gusli_cases_page.json, значения по умолчанию: inc/acf-cases-page.php.
	 * Кнопку «Посмотреть ещё» обслуживает скрипт сайта: он показывает скрытые карточки (атрибут hidden).
	 */

	$defaults = gusli_cases_page_defaults();

	//* Если плагин ACF выключен, страница показывает значения по умолчанию.
	$field = static function (string $key) use ($defaults): string {
		$value = function_exists('get_field') ? trim((string) get_field($key)) : '';

		return $value !== '' ? $value : (string) $defaults[$key];
	};

	//* Карточки: строки повторителя с названием, иначе значения по умолчанию.
	$cards = [];
	foreach ((array) (function_exists('get_field') ? get_field('items') : []) as $row) {
		if (empty($row['title'])) {
			continue;
		}

		$cards[] = [
			'title'    => $row['title'],
			'desc'     => $row['desc'] ?? '',
			'alt'      => $row['alt'] ?? '',
			'image_id' => (int) ($row['image'] ?? 0),
			'image'    => 0,
			'accent'   => $row['accent'] ?? '',
			'tag'      => $row['tag'] ?? '',
			'link'     => $row['link'] ?? '',
		];
	}

	if (!$cards) {
		foreach ($defaults['items'] as $case) {
			$cards[] = $case + ['alt' => '', 'image_id' => 0, 'link' => ''];
		}
	}

	//* Сколько карточек видно сразу, остальные раскрывает кнопка «Посмотреть ещё».
	$visible = function_exists('get_field') ? (int) get_field('visible') : 0;
	$visible = $visible > 0 ? $visible : (int) $defaults['visible'];

	$img_uri = get_template_directory_uri() . '/img/';
?>
<?php get_header(); ?>

<section class="main-content__section top">
	<section class="section-cases">
		<div class="section-cases__container container">
			<div class="section-cases__head"><h2 class="section-cases__title"><?php echo esc_html($field('title')); ?></h2>
				<p class="section-cases__subtitle"><?php echo esc_html($field('subtitle')); ?></p></div>
			<div class="section-cases__grid">
				<?php foreach ($cards as $i => $card) : ?>
					<?php
						// Картинка по умолчанию: число (case-N) или путь внутри img/ без расширения; ссылка пустая, значит, карточка без ссылки.
						$image_name = is_string($card['image']) ? $card['image'] : 'case-' . (int) $card['image'];
						$card_link  = trim((string) $card['link']);
					?>
					<article class="section-cases__card"<?php echo $i >= $visible ? ' hidden=""' : ''; ?>>
						<?php if ($card_link !== '') : ?><a href="<?php echo esc_url($card_link); ?>" style="display:block;color:inherit;text-decoration:none"><?php endif; ?>
						<div class="section-cases__img">
							<?php if ($card['image_id']) : ?>
								<?php echo wp_get_attachment_image($card['image_id'], 'large', false, ['alt' => $card['alt'] ?: $card['title']]); ?>
							<?php else : ?>
								<picture>
									<source srcset="<?php echo esc_url($img_uri . $image_name . '.webp'); ?>" type="image/webp">
									<img src="<?php echo esc_url($img_uri . $image_name . '.png'); ?>"
											alt="<?php echo esc_attr($card['alt'] ?: $card['title']); ?>"></picture>
							<?php endif; ?>
						</div>
						<h3 class="section-cases__name"><?php echo esc_html($card['title']); ?></h3>
						<p class="section-cases__desc"><?php echo esc_html($card['desc']); ?></p>
						<div class="section-cases__tags">
							<?php if ($card['accent'] !== '') : ?><span class="section-cases__tag section-cases__tag--accent"><?php echo esc_html($card['accent']); ?></span><?php endif; ?>
							<?php if ($card['tag'] !== '') : ?><span class="section-cases__tag"><?php echo esc_html($card['tag']); ?></span><?php endif; ?>
						</div>
						<?php if ($card_link !== '') : ?></a><?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if (count($cards) > $visible) : ?>
				<div class="section-cases__more">
					<button class="button button--secondary" type="button" data-cases-more>Посмотреть ещё
					</button>
				</div>
			<?php endif; ?>
		</div>
	</section>
</section>

<?php get_footer(); ?>
