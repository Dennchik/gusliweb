<?php

/**
 * Отправка заявки с формы через AJAX.
 * Обслуживает бриф на странице (form=brief) и форму в подвале (form=footer).
 * Комментарии кода на русском языке.
 */
add_action('wp_ajax_send_cost_form', 'send_cost_form');
add_action('wp_ajax_nopriv_send_cost_form', 'send_cost_form');

/**
 * Читает строковое поле из $_POST, чистит и ограничивает длину.
 */
function gusli_form_value(string $key, int $max = 200, bool $multiline = false): string
{
    $raw   = isset($_POST[$key]) && is_string($_POST[$key]) ? wp_unslash($_POST[$key]) : '';
    $clean = $multiline ? sanitize_textarea_field($raw) : sanitize_text_field($raw);

    return mb_substr($clean, 0, $max);
}

function send_cost_form(): void
{
    // Проверяем одноразовый код формы.
    if (!check_ajax_referer('gusli_form', 'nonce', false)) {
        wp_send_json_error([
            'message' => 'Страница устарела. Обновите её и отправьте заявку ещё раз.',
        ], 403);
    }

    // Ловушка для ботов: настоящий посетитель это поле не видит и не заполняет.
    if (gusli_form_value('website') !== '') {
        wp_send_json_success([
            'message' => 'Заявка успешно отправлена!',
        ]);
    }

    $form    = gusli_form_value('form', 20) === 'footer' ? 'footer' : 'brief';
    $name    = gusli_form_value('name', 100);
    $contact = gusli_form_value('contact', 150);
    $email   = sanitize_email(gusli_form_value('email', 100));
    $text    = gusli_form_value('message', 3000, true);
    $task    = gusli_form_value('task');
    $budget  = gusli_form_value('budget');
    $term    = gusli_form_value('term');
    $package = gusli_form_value('package');
    $sum     = gusli_form_value('sum');

    // Проверяем обязательные поля: у подвала нужна почта, у брифа — согласие.
    $invalid = !$name || !$contact;

    if ($form === 'footer') {
        $invalid = $invalid || !is_email($email);
    } else {
        $invalid = $invalid || empty($_POST['ok']);
    }

    if ($invalid) {
        wp_send_json_error([
            'message' => 'Проверьте правильность заполнения полей.',
        ], 400);
    }

    // Указываем почту получателя.
    $to = 'awdes@yandex.ru';

    // Формируем тему и текст письма.
    $subject = $form === 'footer'
        ? 'Новое сообщение с сайта (форма в подвале)'
        : 'Новая заявка с сайта (бриф)';

    $lines = [
        $form === 'footer' ? 'Сообщение из формы в подвале сайта:' : 'Новая заявка из брифа на сайте:',
        '',
        "Имя: {$name}",
        "Контакт: {$contact}",
    ];

    if (is_email($email)) {
        $lines[] = "Email: {$email}";
    }
    if ($task) {
        $lines[] = "Задача: {$task}";
    }
    if ($budget) {
        $lines[] = "Бюджет: {$budget}";
    }
    if ($term) {
        $lines[] = "Срок: {$term}";
    }
    if ($package) {
        $lines[] = "Подобранный пакет: {$package}";
    }
    if ($sum) {
        $lines[] = "Сборка: {$sum}";
    }
    if ($text) {
        $lines[] = '';
        $lines[] = 'Сообщение:';
        $lines[] = $text;
    }

    $message = implode("\n", $lines) . "\n";

    // Настраиваем заголовки письма.
    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    if (is_email($email)) {
        $headers[] = 'Reply-To: ' . $email;
    }

    // Отправляем письмо.
    if (wp_mail($to, $subject, $message, $headers)) {
        wp_send_json_success([
            'message' => 'Заявка успешно отправлена!',
        ]);
    }

    wp_send_json_error([
        'message' => 'Не удалось отправить заявку. Попробуйте позже.',
    ], 500);
}
