<?php
/*
Template Name: Страница без Бокового меню
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
							<div class="section-greed__line">

								<div class="section-greed__row">
									<div class="section-greed__title">
										<hgroup class="title tl-02">
											<h1><span><?php the_title(); ?></span></h1>
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
