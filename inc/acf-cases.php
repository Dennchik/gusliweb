<?php

	/**
	 * Шестая секция главной страницы «Кейсы» (template-parts/front-page/cases.php).
	 * Поля лежат в acf-json/group_gusli_hero.json (группа «Главная страница»).
	 * Значения по умолчанию показываются, пока поля пустые.
	 *
	 * Ссылки по умолчанию пустые: карточка без ссылки выводится без тега <a>.
	 * Страницы кейсов появятся позже; тогда адрес указывается в поле «Ссылка».
	 */
	function gusli_cases_defaults(): array
	{
		return [
				'title' => 'Примеры решений',
				'link_text' => 'Смотреть все кейсы →',
				'link_url' => home_url('/cases/'),
				'cases' => [
						[
								'title' => 'Сайт студии «Гусли Медиа»',
								'alt' => 'Сайт студии звукозаписи «Гусли Медиа»',
								'image' => '/img/materials/case-1/card_m.png',
								'link' => home_url('/cases/gusli-media/'),
								'tags' => [
										['text' => 'Сайт на React', 'accent' => true],
										['text' => 'Заявки и Метрика', 'accent' => false],
								],
						],
						[
								'title' => 'Сайт благочиния города Обнинска',
								'alt' => 'Сайт 3-го благочиния города Обнинска',
								'image' => '/img/materials/case-2/card_m.png',
								'link' => home_url('/cases/blagoobninsk/'),
								'tags' => [
										['text' => 'Сайт на WordPress', 'accent' => true],
										['text' => 'Собственная тема', 'accent' => false],
								],
						],
						[
								'title' => 'Сайт компании Букеты-Ракеты',
								'alt' => 'Сайт компании Букеты-Ракеты',
								'image' => '/img/materials/case-3/card_m.png',
								'link' => home_url('/cases/buketraket/'),
								'tags' => [
										['text' => 'Оренбург', 'accent' => true],
										['text' => 'Каталог - Запуск 15 дней', 'accent' => false],
								],
						],
				],
		];
	}
