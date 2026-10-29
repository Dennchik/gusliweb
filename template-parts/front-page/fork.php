<?php
	/**
	 * template-parts/front-page/fork.php
	 * Поля ACF: acf-json/group_gusli_hero.json, значения по умолчанию: inc/acf-fork.php.
	 */

	$defaults = gusli_fork_defaults();

	// Группа полей секции. Если плагин ACF выключен, страница показывает значения по умолчанию.
	$fork = function_exists('get_field') ? get_field('fork') : null;
	$fork = is_array($fork) ? $fork : [];

	// Текст из массива полей или значение по умолчанию, если поле пустое.
	$fork_text = static function ($source, string $key, string $default): string {
		$value = is_array($source) ? trim((string) ($source[$key] ?? '')) : '';

		return $value !== '' ? $value : $default;
	};

	// Список строк из повторителя или значения по умолчанию.
	$fork_list = static function ($source, array $default): array {
		$list = [];

		foreach ((array) ($source['items'] ?? []) as $row) {
			if (!empty($row['text'])) {
				$list[] = $row['text'];
			}
		}

		return $list ?: $default;
	};

	$fork_paragraphs = preg_split('/\R+/', $fork_text($fork, 'lead', $defaults['lead']), -1, PREG_SPLIT_NO_EMPTY);

	$scheme = [];
	foreach ($defaults['scheme'] as $key => $default) {
		$scheme[$key] = $fork_text($fork['scheme'] ?? null, $key, $default);
	}

	$tracks = [];
	foreach (['track_a', 'track_b'] as $id) {
		$source = $fork[$id] ?? null;
		$tracks[$id] = [
			'tag'         => $fork_text($source, 'tag', $defaults[$id]['tag']),
			'title'       => $fork_text($source, 'title', $defaults[$id]['title']),
			'lead'        => $fork_text($source, 'lead', $defaults[$id]['lead']),
			'items'       => $fork_list($source, $defaults[$id]['items']),
			'price'       => $fork_text($source, 'price', $defaults[$id]['price']),
			'button_text' => $fork_text($source, 'button_text', $defaults[$id]['button_text']),
			'button_link' => $fork_text($source, 'button_link', $defaults[$id]['button_link']),
		];
	}

	// Ссылка-якорь получает класс плавной прокрутки.
	$fork_link_class = static function (string $link, string $base): string {
		return $base . (str_starts_with($link, '#') ? ' anchor-link' : '');
	};
?>
<section class="main-content__section">
	<div class="section-fork" id="fork">
		<div class="section-fork__body">
			<div class="section-fork__container">
				<div class="section-fork__header sec-head in">
					<div class="section-fork__eye-brow"><?php echo esc_html($fork_text($fork, 'eyebrow', $defaults['eyebrow'])); ?><b><?php echo esc_html($fork_text($fork, 'eyebrow_step', $defaults['eyebrow_step'])); ?></b></div>
					<h2><?php echo wp_kses_post($fork_text($fork, 'title', $defaults['title'])); ?></h2>
					<?php foreach ($fork_paragraphs as $paragraph) : ?>
						<p><?php echo wp_kses_post($paragraph); ?></p>
					<?php endforeach; ?>
				</div>
				<figure class="scheme in">
					<figcaption class="scheme__cap">
						<span><?php echo esc_html($scheme['caption_left']); ?></span><span><?php echo esc_html($scheme['caption_right']); ?></span>
					</figcaption>
					<svg viewBox="0 0 1120 252"
							role="img"
							aria-label="<?php echo esc_attr($scheme['aria']); ?>">
						<g class="tick">
							<path d="M8 170v10M168 170v10M348 170v10M868 170v10M1108 170v10"></path>
						</g>
						<rect class="node" x="8" y="108" width="126" height="46" rx="4"></rect>
						<text x="71" y="128" text-anchor="middle"><?php echo esc_html($scheme['request_title']); ?></text>
						<text class="sub" x="71" y="145" text-anchor="middle"><?php echo esc_html($scheme['request_sub']); ?></text>
						<path class="wire" d="M134 131h34"></path>
						<rect class="node" x="168" y="108" width="180" height="46" rx="4"></rect>
						<text x="258" y="128" text-anchor="middle"><?php echo esc_html($scheme['diag_title']); ?></text>
						<text class="sub" x="258" y="145" text-anchor="middle"><?php echo esc_html($scheme['diag_sub']); ?></text>
						<path class="wire-a" d="M348 131c30 0 30-69 60-69"></path>
						<path class="wire-b" d="M348 131c30 0 30 69 60 69"></path>
						<text class="lbl lbl-a" x="408" y="28"><?php echo esc_html($scheme['a_label']); ?></text>
						<rect class="node node-a" x="408" y="40" width="250" height="44" rx="4"></rect>
						<text x="533" y="59" text-anchor="middle"><?php echo esc_html($scheme['a1_title']); ?></text>
						<text class="sub" x="533" y="75" text-anchor="middle"><?php echo esc_html($scheme['a1_sub']); ?></text>
						<path class="wire-a" d="M658 62h40"></path>
						<rect class="node node-a" x="698" y="40" width="170" height="44" rx="4"></rect>
						<text x="783" y="59" text-anchor="middle"><?php echo esc_html($scheme['a2_title']); ?></text>
						<text class="sub" x="783" y="75" text-anchor="middle"><?php echo esc_html($scheme['a2_sub']); ?></text>
						<rect class="node node-b" x="408" y="176" width="250" height="44" rx="4"></rect>
						<text x="533" y="195" text-anchor="middle"><?php echo esc_html($scheme['b1_title']); ?></text>
						<text class="sub" x="533" y="211" text-anchor="middle"><?php echo esc_html($scheme['b1_sub']); ?></text>
						<path class="wire-b" d="M658 198h40"></path>
						<rect class="node node-b" x="698" y="176" width="170" height="44" rx="4"></rect>
						<text x="783" y="195" text-anchor="middle"><?php echo esc_html($scheme['b2_title']); ?></text>
						<text class="sub" x="783" y="211" text-anchor="middle"><?php echo esc_html($scheme['b2_sub']); ?></text>
						<text class="lbl lbl-b" x="408" y="240"><?php echo esc_html($scheme['b_label']); ?></text>
						<path class="wire-a" d="M868 62c30 0 30 69 60 69"></path>
						<path class="wire-b" d="M868 198c30 0 30-69 60-69"></path>
						<rect class="node" x="928" y="108" width="180" height="46" rx="4"></rect>
						<text x="1018" y="128" text-anchor="middle"><?php echo esc_html($scheme['launch_title']); ?></text>
						<text class="sub" x="1018" y="145" text-anchor="middle"><?php echo esc_html($scheme['launch_sub']); ?></text>
					</svg>
				</figure>
				<div class="fork-box fork in">
					<?php foreach (['track_a' => 'pack', 'track_b' => 'custom'] as $id => $modifier) : ?>
						<?php $track = $tracks[$id]; ?>
						<div class="fork-box__track track--<?php echo esc_attr($modifier); ?>"><span class="track__tag"><?php echo esc_html($track['tag']); ?></span>
							<h3><?php echo wp_kses_post($track['title']); ?></h3>
							<p class="t-lead"><?php echo wp_kses_post($track['lead']); ?></p>
							<ul>
								<?php foreach ($track['items'] as $item) : ?>
									<li><?php echo esc_html($item); ?></li>
								<?php endforeach; ?>
							</ul>
							<div class="fork-box__foot">
								<div class="fork-box__from"><small><?php echo esc_html($track['price']); ?></small></div>
								<?php if ($id === 'track_a') : ?>
									<a class="<?php echo esc_attr($fork_link_class($track['button_link'], 'button button--primary')); ?>" href="<?php echo esc_url($track['button_link']); ?>"><?php echo esc_html($track['button_text']); ?></a>
								<?php else : ?>
									<a class="<?php echo esc_attr($fork_link_class($track['button_link'], 'button button--accent')); ?>" href="<?php echo esc_url($track['button_link']); ?>"><span><?php echo esc_html($track['button_text']); ?></span></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
