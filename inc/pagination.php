<?php
/* inc/pagination.php */

declare(strict_types=1);


/**
 * Вывод пагинации архива.
 */
function theme_pagination(WP_Query $query): void
{
	$total = $query->max_num_pages;

	if ($total <= 1) {
		return;
	}

	$current = max(1, get_query_var('paged'));

	$pages = theme_pagination_pages($current, $total);
	?>

	<nav class="pagination-news" aria-label="Навигация по страницам">
		<?php

		$prev_class = 'navigation__button-prev';

		if ($current === 1) {
			$prev_class .= ' is-disabled';
		}

		?>

		<div class="navigation">

			<a class="<?php echo esc_attr($prev_class); ?>"
			   href="<?php echo esc_url(get_pagenum_link(max(1, $current - 1))); ?>"
			   aria-label="Предыдущая страница">

				<span>
					<img
							class="navigation__arrow"
							src="<?php echo get_template_directory_uri(); ?>/img/frame/arrow.png"
							alt=""
					>
				</span>

			</a>

		</div>
		<ul class="pagination-news__list">

			<?php foreach ($pages as $page) : ?>

				<li class="pagination-news__item<?php echo ((int)$page === $current) ? ' is-active' : ''; ?>">

					<?php if ($page === '...') : ?>

						<span class="pagination__dots">
							...
						</span>


					<?php elseif ((int)$page === $current) : ?>

						<span class="pagination-news__link">
							<?php echo $page; ?>
						</span>

					<?php else : ?>

						<a class="pagination-news__link"
						   href="<?php echo esc_url(get_pagenum_link((int)$page)); ?>">
							<?php echo $page; ?>
						</a>

					<?php endif; ?>

				</li>

			<?php endforeach; ?>

		</ul>

		<?php

		$next_class = 'navigation__button-next';

		if ($current === $total) {
			$next_class .= ' is-disabled';
		}

		?>

		<div class="navigation">
			<a class="<?php echo esc_attr($next_class); ?>"
			   href="<?php echo esc_url(get_pagenum_link(min($total, $current + 1))); ?>"
			   aria-label="Следующая страница">

					<span>
						<img
								class="navigation__arrow"
								src="<?php echo get_template_directory_uri(); ?>/img/frame/arrow.png"
								alt=""
						>
					</span>
			</a>
		</div>
	</nav>

	<?php
}


/**
 * Формирование списка страниц.
 */
function theme_pagination_pages(int $current, int $total): array
{
	$pages = [];

	$range = 1;


	// Всегда первая страница
	$pages[] = 1;


	// Левая часть
	if ($current - $range > 2) {
		$pages[] = '...';
	}


	for (
			$i = max(2, $current - $range);
			$i <= min($total - 1, $current + $range);
			$i++
	) {
		$pages[] = $i;
	}


	// Правая часть
	if ($current + $range < $total - 1) {
		$pages[] = '...';
	}


	// Всегда последняя
	if ($total > 1) {
		$pages[] = $total;
	}


	return $pages;
}