<?php

declare(strict_types=1);

/**
 * Validates the uploaded image
 *
 * @param array $errors Array of errors
 * @param array $data   The form's data array
 *
 * @return void
 */
function processLotImage(array &$errors, array &$data): void {
    if (empty($_FILES[LOT_IMAGE_FIELD]['name'])) {
        $errors[LOT_IMAGE_FIELD] = "Вы не загрузили файл";
        return;
    }

    if ($_FILES[LOT_IMAGE_FIELD]['size'] > 5 * 1024 * 1024) {
        $errors[LOT_IMAGE_FIELD] = "Файл слишком большой (макс. 5 MB)";
        return;
    }

    $tmp_name = $_FILES[LOT_IMAGE_FIELD]['tmp_name'];
    $file_type = mime_content_type($tmp_name);
    $extension = strtolower(pathinfo($_FILES[LOT_IMAGE_FIELD]['name'], PATHINFO_EXTENSION));

    if (
        !in_array($file_type, ALLOWED_IMAGE_MIME_TYPES, true) ||
        !in_array($extension, ALLOWED_IMAGE_EXTENSIONS, true)
    ) {
        $errors[LOT_IMAGE_FIELD] = "Допустимы файлы: .png, .jpg, .jpeg";
        return;
    }

    $filename = uniqid() . '.' . $extension;

    if (move_uploaded_file($tmp_name, 'uploads/' . $filename)) {
        $data[LOT_IMAGE_FIELD] = 'uploads/' . $filename;
    } else {
        $errors[LOT_IMAGE_FIELD] = "Ошибка загрузки файла";
    };
}
