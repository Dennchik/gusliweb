<?php

	declare(strict_types=1);

	/**
	 * inc/helpers.php
	 */


//* Оставляет только цифры:
	function toNumber(string $text): string
	{
		return preg_replace('/\D+/', '', sanitize_text_field($text));
	}


//* Возвращает отформатированную дату из ACF:
	function acf_date(
			string $field = 'дата',
			       $post_id = null,
			string $format = 'j F Y H:i'
	): string
	{

		$post_id ??= get_the_ID();

		$value = get_field($field, $post_id);

		if (empty($value)) {
			return '';
		}

		$date = DateTime::createFromFormat('YmdHi', $value);

		if (!$date) {
			return '';
		}

		return wp_date($format, $date->getTimestamp());
	}


//* Обрезает количество строк в таблицах:
	function limit_table_rows(string $html, int $max_rows = 3): string
	{
		libxml_use_internal_errors(true);

		$dom = new DOMDocument();
		$dom->loadHTML('<?xml encoding="UTF-8">' . $html);

		libxml_clear_errors();

		foreach ($dom->getElementsByTagName('table') as $table) {

			$rows = $table->getElementsByTagName('tr');

			for ($i = $rows->length - 1; $i >= $max_rows; $i--) {

				$row = $rows->item($i);

				if ($row) {
					$row->parentNode->removeChild($row);
				}
			}
		}

		$body = $dom->getElementsByTagName('body')->item(0);

		$result = '';

		if ($body) {

			foreach ($body->childNodes as $child) {
				$result .= $dom->saveHTML($child);
			}

		}

		return $result;
	}


//* Разбивает строку на части по разделителю:
	/*
	 * <?php $parts = split_text(get_the_title()); ?>
	 * <?php $parts = split_text($hero_title); ?>
	*/

	function split_text(
			string $text,
			string $delimiter = '||'
	): array
	{
		return array_filter(
				array_map('trim', explode($delimiter, $text))
		);
	}