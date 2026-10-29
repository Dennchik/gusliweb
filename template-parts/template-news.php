<?php
/*
Template Name: Раздел - Новости
*/
?>

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

								</aside>
							</div>

							<div class="section-greed__column">
								<div class="section-greed__bread-crumbs">
									<?php theme_breadcrumbs(); ?>
								</div>
								<div class="section-greed__row">
									<div class="section-greed__title">
										<?php
										$title = get_the_title();
										$parts = array_map('trim', explode('||', $title));
										?>

										<hgroup class="title tl-02 section-greed__title">
											<h1>
												<?php foreach ($parts as $part) : ?>
													<span><?php echo esc_html($part); ?></span>
												<?php endforeach; ?>
											</h1>
										</hgroup>
									</div>
									<div class="info-content">
										<div class="info-content__column">
											<div class="info-content__row">

												<?php if (have_rows('page_builder')) : ?>
													<?php while (have_rows('page_builder')) : the_row(); ?>
														<?php
														$layout = get_row_layout();
														get_template_part('template-parts/flexible/' . $layout); ?>
													<?php endwhile; ?>
												<?php endif; ?>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<?php get_footer(); ?>
