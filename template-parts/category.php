<!DOCTYPE html>
<html lang="ru">
<!-- @noinspection PhpRedundantOptionalArgumentInspection *//** @noinspection ALL -->
<?php
	wp_head();
	$version = wp_get_theme()->get('Version');
	$version = $version ?: time(); ?>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php wp_head(); ?>

	<link rel="icon" type="image/icon" href="<?php bloginfo('template_url'); ?>/img/icons/logo.png">
	<title>Официальный сайт 3го благочиния города Обнинска</title>
	<script type="module"
	        crossorigin
	        src="<?php bloginfo('template_url'); ?>/assets/vendors/modulepreload-polyfill.js?ver=<?php echo $version; ?>"></script>
	<script type="module"
	        crossorigin
	        src="<?php bloginfo('template_url'); ?>/assets/vendors/vendor.js?ver=<?php echo $version; ?>"></script>
	<script type="module"
	        crossorigin
	        src="<?php bloginfo('template_url'); ?>/assets/app.js?ver=<?php echo $version; ?>"></script>
	<script type="module"
	        crossorigin
	        src="<?php bloginfo('template_url'); ?>/assets/main.js?ver=<?php echo $version; ?>"></script>
	<link rel="stylesheet"
	      crossorigin
	      href="<?php bloginfo('template_url'); ?>/assets/main.css?ver=<?php echo $version; ?>">
	<link rel="stylesheet"
	      crossorigin
	      href="<?php bloginfo('template_url'); ?>/style.css?ver=<?php echo $version; ?>">
	<?php if (current_user_can('administrator')): ?>
		<style>
					.page__header {
						margin-top: 30px;
					}

					@media screen and (max-width: 782px) {
						.page__header {
							margin-top: 46px;
						}
					}
		</style>
	<?php endif; ?>
</head>

<body <?php body_class(); ?>>
<div class="preloader"><span class="preloader__loader"> </span></div>
<div class="wrapper">
	<div class="page">

		<?php get_header(); ?>

		<main class="page__main-content">
			<div class="main-content" id="wrapper">
				<div class="main-content__content" id="content">

					<section class="main-content__section section-top">
						<div class="section-greed _container">
							<div class="section-greed__body">
								<div class="mask-render"></div>
								<div class="bg-grad"></div>
								<div class="section-greed__content">
									<div class="section-greed__column">
										<aside class="aside-menu"
										       data-da="bp-1"
										       data-da-position="1"
										       data-da-resolution="690">
											<ul class="aside-menu__list">
												<li class="aside-menu__item">
													<span class="aside-menu__link link-hover">Новости</span>
												</li>
												<li class="aside-menu__item">
													<a class="aside-menu__link" href="#">
														<span>Пресса о нас</span>
													</a>
												</li>
												<li class="aside-menu__item">
													<a class="aside-menu__link" href="#">
														<span>Документы</span>
													</a>
												</li>
											</ul>
										</aside>
									</div>
									<div class="section-greed__column">
										<div class="section-greed__bread-crumbs">
											<nav class="bread-crumbs">
												<ul class="bread-crumbs__list">
													<li><a class="bread-crumbs__link link_underline" href="./index/"
													       aria-label="home">Главная </a></li>
													<li><i class="icon-angle-right _icon"></i></li>
													<li><span class="bread-crumbs__item">Новости</span></li>
												</ul>
											</nav>
										</div>
										<div class="section-greed__row">
											<hgroup class="title tl-02 section-greed__title">
												<h2><span>Новости благочиния</span></h2>
											</hgroup>
										</div>
										<div class="section-greed__row">
											<div class="hr">
												<picture>
													<source srcset="<?php bloginfo('template_url'); ?>/img/frame/hr.webp"
													        type="image/webp">
													<img src="<?php bloginfo('template_url'); ?>/img/frame/hr.png"
													     alt="image" loading="lazy">
												</picture>
											</div>
											<div class="gallery-news section-greed__news">

												<div class="gallery-news__body">

													<?php
														// ★★★ ДОБАВЛЯЕМ ФУНКЦИЮ ОБРЕЗКИ ТАБЛИЦ ★★★
														$content = apply_filters('the_content', get_the_content());

														$content = limit_table_rows($content);

														// ★★★ КОНЕЦ ФУНКЦИИ ★★★

														$current_category = get_queried_object();
														$cat_id = $current_category->term_id;

														$args = array(
																'post_type' => 'post',
																'posts_per_page' => 50,
																'cat' => $cat_id,
														);

														$query = new WP_Query($args);
														$items = [];

														if ($query->have_posts()) :

															while ($query->have_posts()) : $query->the_post();
																$raw = get_field('дата');
																if (!$raw) continue;
																$date = DateTime::createFromFormat('YmdHi', $raw);
																if (!$date) continue;
																$items[] = [
																		'post' => get_post(),
																		'time' => $date->getTimestamp(),
																];
															endwhile;

															wp_reset_postdata();

															usort($items, function ($a, $b) {
																return $b['time'] <=> $a['time'];
															});

															foreach ($items as $item) :
																$post = $item['post'];
																setup_postdata($post);
																?>

																<div class="gallery-news__line">

																	<div class="gallery-news__image _img">
																		<?php if (has_post_thumbnail()) : ?>
																			<?php echo theme_image(get_post_thumbnail_id(), get_the_title()); ?>
																		<?php endif; ?>

																		<div class="gallery-news__label">
																			<span class="gallery-news__data">
																				<?php
																					$raw = get_field('дата');
																					$date = DateTime::createFromFormat('YmdHi', $raw);
																					if ($date) {
																						echo date_i18n('j F Y H:i', $date->getTimestamp());
																					}
																				?>
																			</span>
																		</div>
																	</div>

																	<div class="news-content">
																		<div class="news-content__body">
																			<div class="news-content__title">
																				<strong><?php the_title(); ?></strong>
																			</div>
																			<div class="news-content__text">
																				<?php
																					// ★★★ ИСПРАВЛЕННЫЙ БЛОК ВЫВОДА КОНТЕНТА ★★★
																					$content = apply_filters('the_content', get_the_content());

																					// ОБРЕЗАЕМ ВСЕ ТАБЛИЦЫ ДО 3 СТРОК
																					$content = limit_table_rows($content);

																					$paragraphs = explode("</p>", $content);
																					// Берём первые 3 абзаца (индексы 0,1,2)
																					$first_three = array_slice($paragraphs, 0, 3);
																					echo implode("</p>", $first_three) . "</p>";
																				?>
																			</div>
																			<div class="news-content__link">
																				<a class="link_underline"
																				   href="<?php the_permalink(); ?>">
																					Читать далее
																				</a>
																			</div>
																		</div>
																	</div>

																</div>

															<?php
															endforeach;
															wp_reset_postdata();
														else :
															echo '<p>Новостей нет</p>';
														endif;
													?>

												</div>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>


					<?php get_footer(); ?>


				</div>
			</div>
		</main>
		<nav class="page__menu-list">
			<div class="menu-list" data-sidebar>
				<div class="menu-list__body">
					<div class="menu-list__header">
						<div class="menu-list__title">
							<h3>Благочиние г. Обнинска</h3>
							<div class="burger-button">
								<span class="burger-button__line"></span>
								<span class="burger-button__line"></span>
								<span class="burger-button__line"> </span>
							</div>
						</div>
					</div>
					<div class="menu-list__content bp-1"></div>
				</div>
			</div>
		</nav>
		<div class="return-button" id="scrollButton">
			<div class="return-button__arrow">
				<picture>
					<source srcset="<?php bloginfo('template_url'); ?>/img/frame/arrow.webp" type="image/webp">
					<img src="<?php bloginfo('template_url'); ?>/img/frame/arrow.png" alt="image" loading="lazy">
				</picture>
			</div>
		</div>
	</div>
</div>
<?php wp_footer(); ?>
</body>

</html>