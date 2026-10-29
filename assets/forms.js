/**
 * Отправка форм сайта на AJAX-обработчик WordPress (action send_cost_form).
 *
 * Слушатель вешается прямо на форму и гасит событие (stopPropagation), поэтому
 * демо-обработчики из собранного бандла (vendors/layouts.js) на этих формах не срабатывают.
 */
(function () {
	'use strict';

	var config = window.gusliForms;

	if (!config) {
		return;
	}

	var GENERIC_ERROR = 'Не удалось отправить заявку. Попробуйте позже.';

	function value(form, name) {
		var field = form.elements[name];

		return field && typeof field.value === 'string' ? field.value.trim() : '';
	}

	function post(data) {
		var body = new URLSearchParams();

		body.append('action', 'send_cost_form');
		body.append('nonce', config.nonce);

		Object.keys(data).forEach(function (key) {
			body.append(key, data[key]);
		});

		return fetch(config.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: body.toString()
		}).then(function (response) {
			return response.json().catch(function () {
				return { success: false, data: { message: GENERIC_ERROR } };
			});
		});
	}

	function setBusy(button, busy) {
		if (!button) {
			return;
		}

		button.disabled = busy;
		button.setAttribute('aria-busy', busy ? 'true' : 'false');
	}

	function errorMessage(result) {
		return result && result.data && result.data.message ? result.data.message : GENERIC_ERROR;
	}

	/* Бриф в основной части страницы. */
	function initBrief(form) {
		var sentBox = document.getElementById('sentBox');
		var sentText = document.getElementById('sentText');
		var errorBox = document.getElementById('formErr');
		var defaultError = errorBox ? errorBox.textContent : '';
		var button = form.querySelector('[type="submit"]');

		function showError(text) {
			if (!errorBox) {
				return;
			}

			errorBox.textContent = text;
			errorBox.style.display = 'block';
		}

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			event.stopPropagation();

			// Выпадающие списки хранят выбор в кнопке — переносим его в скрытые поля.
			form.querySelectorAll('[data-select]').forEach(function (select) {
				var hidden = select.querySelector('input[type="hidden"]');
				var selected = select.querySelector('.select__button');

				if (hidden && selected) {
					hidden.value = selected.value || selected.textContent.trim();
				}
			});

			var name = value(form, 'user-name');
			var contact = value(form, 'contact');
			var consent = form.elements.ok && form.elements.ok.checked;

			if (!name || !contact || !consent) {
				showError(defaultError);
				return;
			}

			if (errorBox) {
				errorBox.style.display = 'none';
			}

			var packageName = document.getElementById('rName');
			var sum = document.getElementById('sum');

			var data = {
				form: 'brief',
				name: name,
				contact: contact,
				task: value(form, 'task'),
				budget: value(form, 'budget'),
				term: value(form, 'term'),
				message: value(form, 'about'),
				package: packageName ? packageName.textContent.trim() : '',
				sum: sum ? sum.textContent.trim() : '',
				website: value(form, 'website'),
				ok: consent ? '1' : ''
			};

			setBusy(button, true);

			post(data).then(function (result) {
				if (!result.success) {
					showError(errorMessage(result));
					return;
				}

				if (sentText) {
					sentText.textContent = [
						'Имя: ' + data.name,
						'Контакт: ' + data.contact,
						'Задача: ' + data.task,
						'Бюджет: ' + data.budget,
						'Срок: ' + data.term,
						'',
						'О проекте:',
						data.message || '—'
					].join('\n');
				}

				form.reset();
				form.style.display = 'none';

				if (sentBox) {
					sentBox.classList.add('on');
					sentBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
				}
			}).catch(function () {
				showError(GENERIC_ERROR);
			}).then(function () {
				setBusy(button, false);
			});
		});
	}

	/* Форма в подвале. */
	function initFooter(form) {
		var okBox = form.querySelector('.form-card__ok');
		var errorBox = form.querySelector('.form-card__err');
		var button = form.querySelector('[type="submit"]');
		var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

		function showError(text) {
			if (okBox) {
				okBox.hidden = true;
			}

			if (errorBox) {
				errorBox.textContent = text;
				errorBox.hidden = false;
			}
		}

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			event.stopPropagation();

			var name = value(form, 'contact-name');
			var phone = value(form, 'contact-phone');
			var email = value(form, 'contact-email');

			if (!name || !phone || !emailPattern.test(email)) {
				showError('Заполните имя, телефон и корректный e-mail.');
				return;
			}

			if (errorBox) {
				errorBox.hidden = true;
			}

			setBusy(button, true);

			post({
				form: 'footer',
				name: name,
				contact: phone,
				email: email,
				message: value(form, 'message'),
				website: value(form, 'website')
			}).then(function (result) {
				if (!result.success) {
					showError(errorMessage(result));
					return;
				}

				form.reset();

				if (okBox) {
					okBox.hidden = false;
				}
			}).catch(function () {
				showError(GENERIC_ERROR);
			}).then(function () {
				setBusy(button, false);
			});
		});
	}

	var brief = document.getElementById('briefForm');
	var footer = document.getElementById('footerForm');

	if (brief) {
		initBrief(brief);
	}

	if (footer) {
		initFooter(footer);
	}
})();
