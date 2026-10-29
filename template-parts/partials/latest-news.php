<?php
/** template-parts/partials/latest-news.php */
$query = new WP_Query([
		'post_type' => 'post',
		'posts_per_page' => 1,
		'category_name' => 'news',
		'meta_key' => 'дата',
		'orderby' => 'meta_value',
		'order' => 'DESC',
]);

if ($query->have_posts()) :

	?>

	<div class="latest-news">

		<?php
		while ($query->have_posts()) :

			$query->the_post();

			get_template_part(
					'template-parts/partials/news-card'
			);

		endwhile;
		?>

	</div>

<?php

endif;

wp_reset_postdata();