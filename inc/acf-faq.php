<?php

	/**
	 * Восьмая секция главной страницы «Вопросы» (template-parts/front-page/faq-section.php).
	 * Поля лежат в acf-json/group_gusli_hero.json (группа «Главная страница»).
	 * Если поля ACF пустые, значения по умолчанию не выводятся.
	 */
	function gusli_faq_defaults(): array
	{
		return [
				'eyebrow' => '',
				'title'   => '',
				'items'   => [],
		];
	}