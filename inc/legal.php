<?php

/**
 * Юридические страницы (политики, согласия): адреса для ссылок подвала.
 * Шаблон самих страниц: page-legal.php.
 */

/**
 * Адрес опубликованной страницы по её ярлыку (slug). Если страницы нет или она не опубликована, возвращает «#».
 */
function gusli_legal_url(string $slug): string
{
    $page = get_page_by_path($slug);

    return ($page && $page->post_status === 'publish') ? get_permalink($page) : '#';
}
