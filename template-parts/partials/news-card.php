<?php
/* template-parts/partials/news-card.php */

declare(strict_types=1);

$image_ratio = get_field('image_ratio');

if (!$image_ratio) {
	$image_ratio = 'ratio-default';
}
?>

<div class="gallery-news__line">

	<div class="gallery-news__image _img <?= esc_attr($image_ratio); ?>">

		<?php if (has_post_thumbnail()) : ?>

			<?php echo theme_image(get_post_thumbnail_id(), get_the_title()); ?>

		<?php endif; ?>


		<div class="gallery-news__label">

			<span class="gallery-news__data">

				<?php
				$raw = get_field('дата');

				$date = DateTime::createFromFormat('YmdHi', $raw);

				if ($date) {
					echo date_i18n(
							'j F Y H:i',
							$date->getTimestamp()
					);
				}
				?>

			</span>

		</div>
	</div>


	<div class="news-content">

		<div class="news-content__body">

			<div class="news-content__title">

				<strong>
					<?php the_title(); ?>
				</strong>
			</div>

			<div class="news-content__text">

				<?php theme_news_excerpt(get_the_ID()); ?>

			</div>

			<a class="news-content__link" href="<?php the_permalink(); ?>">
				<span>
					Читать далее
				</span>
			</a>
		</div>
	</div>
</div>