<?php
/**
 * Template Name: Юридический документ (Gusli Web)
 * Шаблон юридических страниц (политики, согласия): текст страницы внутри шапки и подвала сайта.
 * Ссылки на эти страницы в подвале: footer.php, функция gusli_legal_url() из inc/legal.php.
 */

get_header();
?>

<div class="main-content__section top">
	<?php
	while (have_posts()) {
		the_post();
		the_content();
	}
	?>
</div>

<?php get_footer(); ?>
