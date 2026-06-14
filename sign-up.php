<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

use enum\HttpMethodEnum;

/** @var mysqli $connection */
/** @var array $auth_user */
/** @var array  $categories */

$errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === HttpMethodEnum::POST->value) {
    $form_data = array_map('trim', $_POST);
    $email = $form_data[EMAIL_FIELD] ?? '';
    $user = getUserByEmail($connection, $email);

    validateFormData(VALIDATION_RULES[SIGN_UP_FORM_KEY], $form_data, $errors, $user);

    $errors = array_filter($errors);

    if (empty($errors)) {
        $data = prepareUserData($form_data);
        $user_id = addUser($connection, $data);

        if ($user_id) {
            header("Location: login.php");
            exit;
        }
    }
}

$page_content = include_template('sign-up.php', compact(
    'categories',
    'form_data',
    'errors'
));

/** @noinspection PhpPipeOperatorCanBeUsedInspection */
$layout_content = include_template('layout/main.php', array_merge(
    [
        'title' => 'Регистрация'
    ],
    compact(
        'page_content',
        'categories',
        'auth_user'
    )
));

print($layout_content);
