<?php
/* template-parts/single/post.php */
declare(strict_types=1);
?>

<main class="page__main-content">
	<div class="main-content" id="wrapper">
		<div class="main-content__content" id="content">
			<section class="main-content__section section-top">
				<div class="section-greed _container">
					<div class="section-greed__body">
						<div class="section-greed__content">
							<div class="section-greed__column">
								<aside class="aside-menu" data-da="bp-1" data-da-position="1" data-da-resolution="690">

									<?php
									wp_nav_menu([
											'theme_location' => 'news_menu',
											'container' => false,
											'menu_id' => false,
											'menu_class' => 'aside-menu__list',
											'items_wrap' => '<ul class="%2$s">%3$s</ul>',
											'walker' => new Aside_Menu_Walker(),
									]);
									?>
									<?php get_template_part('template-parts/partials/latest-news'); ?>
								</aside>
							</div>
							<div class="section-greed__column">
								<div class="section-greed__bread-crumbs">
									<?php theme_breadcrumbs(); ?>
								</div>
								<div class="section-greed__row">
									<div class="section-greed__title">
										<hgroup class="title tl-02">
											<h1>
												<span>
													<?php the_title(); ?>
												</span>
											</h1>
										</hgroup>
									</div>


									<div class="info-content">
										<div class="info-content__column">
											<div class="info-content__row">
												<?php
												$raw_date = get_field('дата');

												if ($raw_date) :

													$date = DateTime::createFromFormat('YmdHi', $raw_date);

													if ($date) :
														?>

														<div class="news-date">
												<span>
													<?php echo esc_html(
															date_i18n('j F Y H:i', $date->getTimestamp())
													); ?>
												</span>
														</div>

													<?php
													endif;

												endif;
												?>
												<?php get_template_part('template-parts/page-builder'); ?>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</main>