<?php

declare(strict_types=1);

/**
 * inc/calendar.php
 */


//* Получает события календаря за выбранную неделю:
function get_calendar_week_days(
		array $days,
		int   $week_start,
		int   $week_end
): array
{
	$result = [];

	foreach ($days as $day) {

		if (empty($day['дата'])) {
			continue;
		}

		$date_obj = DateTime::createFromFormat('d.m.Y', $day['дата']);

		if (!$date_obj) {
			continue;
		}

		$timestamp = $date_obj->getTimestamp();

		if ($timestamp >= $week_start && $timestamp <= $week_end) {
			$day['_timestamp'] = $timestamp;
			$result[] = $day;
		}
	}

	return $result;
}


//* Расчёт диапазона текущей недели:
function get_week_range(int $timestamp): array
{
	$weekday_num = (int)date('w', $timestamp);

	if ($weekday_num === 0) {
		$week_start = strtotime('today', $timestamp);
	} else {
		$week_start = strtotime('last sunday', $timestamp);
		$week_start = strtotime('today', $week_start);
	}

	$week_end = strtotime('next saturday 23:59:59', $week_start);

	return [
			'start' => $week_start,
			'end' => $week_end,
	];
}