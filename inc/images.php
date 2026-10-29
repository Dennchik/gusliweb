<?php
declare(strict_types=1);
/**
 * inc/images.php
 */

//* - Конвертирует путь изображения в путь к WebP:
function webp_path(string $filepath): string
{
	return (string)preg_replace('/\.(jpe?g|png)$/i', '.webp', $filepath);
}

//* - Конвертация всех загруженных изображений в WebP:
add_filter('wp_generate_attachment_metadata', 'convert_all_sizes_to_webp', 10, 2);

function convert_all_sizes_to_webp(array $metadata, int $attachment_id): array
{
	$upload_dir = wp_upload_dir();
	$base_dir = trailingslashit($upload_dir['basedir']);
	$file = get_attached_file($attachment_id);
	if (!$file || !file_exists($file)) {
		return $metadata;
	}
	$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
	if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
		return $metadata;
	}

	// todo  -  Оригинал
	$webp_file = webp_path($file);
	$editor = wp_get_image_editor($file);

	if (!is_wp_error($editor)) {
		$editor->save($webp_file, 'image/webp');
	}

	// todo  -   Все размеры изображений

	if (!empty($metadata['sizes'])) {
		foreach ($metadata['sizes'] as $size => $size_data) {
			$size_file = $base_dir . '/' . dirname($metadata['file']) . '/' . $size_data['file'];
			if (!file_exists($size_file)) {
				continue;
			}
			$editor = wp_get_image_editor($size_file);
			if (is_wp_error($editor)) {
				continue;
			}
			$webp_size = webp_path($size_file);
			$saved = $editor->save($webp_size, 'image/webp');
			if (is_wp_error($saved)) {
				continue;
			}

			$metadata['sizes'][$size . '_webp'] = [
					'file' => basename($webp_size),
					'width' => $size_data['width'],
					'height' => $size_data['height'],
					'mime-type' => 'image/webp',
			];
		}
	}

	//todo -  WebP оригинала

	$metadata['sizes']['full_webp'] = [
			'file' => basename($webp_file),
			'width' => $metadata['width'],
			'height' => $metadata['height'],
			'mime-type' => 'image/webp',
	];

	return $metadata;
}

//*  Генерация HTML <picture>:
function picture_html(
		string $url,
		bool   $lazy = true,
		string $atts = '',
		string $alt = 'Изображение'
): string
{
	if ($url === '') {
		return '';
	}
	$upload = wp_upload_dir();
	$img_url = $url;
	$ext = strtolower(
			pathinfo(
					parse_url($url, PHP_URL_PATH),
					PATHINFO_EXTENSION
			)
	);

	if ($ext === 'webp') {
		foreach (['jpg', 'jpeg', 'png'] as $original_ext) {
			$try_url = preg_replace('/\.webp$/i', '.' . $original_ext, $url);
			$local_path = str_replace(
					$upload['baseurl'],
					$upload['basedir'],
					$try_url
			);

			if (file_exists($local_path)) {
				$img_url = $try_url;
				break;
			}
		}
		$source_url = $url;
	} else if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
		$source_url = preg_replace(
				'/\.(jpe?g|png)$/i',
				'.webp',
				$url
		);
	} else {
		$source_url = null;
	}
	$lazy_attr = $lazy
			? 'loading="lazy" decoding="async"'
			: '';
	ob_start();
	?>
	<picture>
		<?php if ($source_url) : ?>
			<source
					srcset="<?php echo esc_url($source_url); ?>"
					type="image/webp"
			>

		<?php endif; ?>

		<img
				src="<?php echo esc_url($img_url); ?>"
				alt="<?php echo esc_attr($alt); ?>"
				<?php echo $lazy_attr; ?>

				<?php if ($atts) {
					echo ' ' . trim($atts);
				} ?>
		>
	</picture>

	<?php
	return trim(ob_get_clean());
}

// * Универсальный вывод изображения темы:
/**
 * Принимает:
 * -URL
 * -ID вложения
 * -ACF Image array
 * */

function theme_image(
		mixed  $image,
		string $alt = 'Изображение',
		bool   $lazy = true,
		string $atts = ''
): string
{

	if (!$image) {
		return '';
	}

	/*
	 * Получаем URL
	 */
	if (is_array($image)) {

		$url = $image['url'] ?? '';

		if (!empty($image['alt'])) {
			$alt = $image['alt'];
		}

	} else if (is_numeric($image)) {
		$image_data = wp_get_attachment_image_src(
				(int)$image,
				'full'
		);
		$url = $image_data[0] ?? '';
		$image_alt = get_post_meta(
				(int)$image,
				'_wp_attachment_image_alt',
				true
		);

		if ($image_alt) {
			$alt = $image_alt;
		}

	} else {

		$url = $image;

	}

	if (!$url) {
		return '';
	}

	return picture_html(
			$url,
			$lazy,
			$atts,
			$alt
	);
}