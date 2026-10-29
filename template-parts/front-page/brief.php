<?php
	/**
	 * template-parts/front-page/brief.php
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-brief.php.
	 * Имена полей формы и id блоков нужны скриптам (assets/forms.js, vendors/*.js), их не менять.
	 */

	$defaults = gusli_brief_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$brief = function_exists('get_field') ? get_field('brief') : null;
	$brief = is_array($brief) ? $brief : [];

	// Текст из поля или значение по умолчанию, если поле пустое.
	$brief_text = static function (string $key) use ($brief, $defaults): string {
		$value = trim((string) ($brief[$key] ?? ''));

		return $value !== '' ? $value : (string) $defaults[$key];
	};

	// Каналы связи: строки повторителя со значением, иначе значения по умолчанию.
	$channels = [];
	foreach ((array) ($brief['channels'] ?? []) as $row) {
		if (!empty($row['label'])) {
			$channels[] = ['label' => $row['label'], 'value' => $row['value'] ?? '', 'link' => ($row['link'] ?? '') ?: '#brief'];
		}
	}
	$channels = $channels ?: $defaults['channels'];

	// Выпадающие списки: варианты из повторителя и номер варианта по умолчанию (с единицы).
	$selects = [];
	foreach ($defaults['selects'] as $id => $default) {
		$options = [];
		foreach ((array) ($brief[$id . '_options'] ?? []) as $row) {
			if (!empty($row['text'])) {
				$options[] = $row['text'];
			}
		}
		$options = $options ?: $default['options'];

		$caption = trim((string) ($brief[$id . '_caption'] ?? ''));
		$index   = (int) ($brief[$id . '_default'] ?? 0);
		$index   = ($index >= 1 && $index <= count($options)) ? $index : min($default['default'], count($options));

		$selects[$id] = [
			'caption' => $caption !== '' ? $caption : $default['caption'],
			'options' => $options,
			'current' => $options[$index - 1],
		];
	}

	// Один выпадающий список в разметке, которую ждёт скрипт select.js.
	$render_select = static function (string $id) use ($selects): void {
		$select = $selects[$id];
		?>
		<div data-select="<?php echo esc_attr($id); ?>">
			<span class="select__caption"><?php echo esc_html($select['caption']); ?></span><input type="hidden"
					name="<?php echo esc_attr($id); ?>"
					value="<?php echo esc_attr($select['current']); ?>">
			<div class="select">
				<button class="select__button"
						id="brief-<?php echo esc_attr($id); ?>"
						type="button"
						value="<?php echo esc_attr($select['current']); ?>"><?php echo esc_html($select['current']); ?></button>
				<ul class="select__list _collapse">
					<?php foreach ($select['options'] as $option) : ?>
						<li class="select__list-item<?php echo $option === $select['current'] ? ' _selected' : ''; ?>" tabindex="0"><?php echo esc_html($option); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
	};
?>
<section class="main-content__section">
	<div class="section-brief cta" id="brief">
		<div class="section-brief__body">
			<div class="section-brief__container">
				<div class="section-brief__line">
					<div class="section-brief__eye-brow"><?php echo esc_html($brief_text('eyebrow')); ?></div>
					<h2><?php echo wp_kses_post($brief_text('title')); ?></h2>
					<p class="section-brief__lead"><?php echo wp_kses_post($brief_text('lead')); ?></p>
					<div class="section-brief__chan chan in" data-rv="">
						<?php foreach ($channels as $channel) : ?>
							<a href="<?php echo esc_url($channel['link']); ?>"><?php echo esc_html($channel['label']); ?><span><?php echo esc_html($channel['value']); ?></span></a>
						<?php endforeach; ?>
					</div>
					<p class="section-brief__note" style="margin-top: 18px"><?php echo wp_kses_post($brief_text('note')); ?></p></div>
				<div class="section-brief__line">
					<form class="section-brief__form in" id="briefForm" novalidate autocomplete="off">
						<div class="section-brief__row"><label for="user-name"><?php echo esc_html($brief_text('name_label')); ?><input id="user-name"
										type="text"
										name="user-name"
										placeholder="<?php echo esc_attr($brief_text('name_placeholder')); ?>"
										autocomplete="off"
										required></label><label for="user-phone"><?php echo esc_html($brief_text('contact_label')); ?><input class="phone"
										id="user-phone"
										type="tel"
										name="contact"
										placeholder="<?php echo esc_attr($brief_text('contact_placeholder')); ?>"
										autocomplete="off"
										required></label></div>
						<?php $render_select('task'); ?>
						<div class="section-brief__row">
							<?php $render_select('budget'); ?>
							<?php $render_select('term'); ?>
						</div>
						<label><?php echo esc_html($brief_text('about_label')); ?><textarea name="about"
									placeholder="<?php echo esc_attr($brief_text('about_placeholder')); ?>"
									autocomplete="off"></textarea></label><label class="section-brief__consent"><input
									type="checkbox"
									name="ok"><span><?php echo esc_html($brief_text('consent')); ?></span></label>
						<input type="text" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true"
								style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0">
						<button class="button button--primary" type="submit"><?php echo esc_html($brief_text('submit')); ?></button>
						<p class="section-brief__note" id="formErr"><?php echo esc_html($brief_text('error')); ?></p>
						<p class="section-brief__note"><?php echo esc_html($brief_text('foot')); ?></p></form>
					<div class="section-brief__sent" id="sentBox"><h3><?php echo esc_html($brief_text('sent_title')); ?></h3>
						<p class="section-brief__note"><?php echo esc_html($brief_text('sent_text')); ?></p>
						<pre id="sentText"></pre>
						<div style="display: flex; gap: 10px; flex-wrap: wrap">
							<button class="button button--accent"
									id="againBtn"
									style="color: var(--night-text); border-color: var(--night-line);"><span>Изменить</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
