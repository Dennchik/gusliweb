<?php

	/**
	 * Седьмая секция главной страницы «Процесс» (template-parts/front-page/process.php).
	 * Поля лежат в acf-json/group_gusli_hero.json (группа «Главная страница»).
	 * Если поля ACF пустые, значения по умолчанию не выводятся.
	 */
	function gusli_process_defaults(): array
	{
		return [
				'eyebrow'    => '',
				'title'      => '',
				'lead'       => '',
				'steps'      => [],
				'need_label' => '',
				'need_text'  => '',
		];
	}