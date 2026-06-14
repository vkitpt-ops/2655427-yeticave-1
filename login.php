<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

use enum\HttpStatusCodeEnum;
use enum\HttpMethodEnum;

/** @var mysqli $connection */
/** @var array $auth_user */
/** @var array  $categories */

if (isset($auth_user['id'])) {
    http_response_code(HttpStatusCodeEnum::HttpForbidden->value);
    exit();
}

$errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === HttpMethodEnum::POST->value) {
    $form_data = array_map('trim', $_POST);
    $email = $form_data[EMAIL_FIELD] ?? '';
    $user = getUserByEmail($connection, $email);

    validateFormData(VALIDATION_RULES[LOGIN_FORM_KEY], $form_data, $errors, $user);

    $errors = array_filter($errors);

    if (empty($errors)) {
        $_SESSION[USER_SESSION_KEY] = [
            'id'   => $user['id'],
            'name' => $user['name']
        ];
        header("Location: index.php");
        exit;
    }
}

$page_content = include_template('login.php', compact(
    'categories',
    'errors',
    'form_data'
));

/** @noinspection PhpPipeOperatorCanBeUsedInspection */
$layout_content = include_template('layout/main.php', array_merge(
    [
        'title' => 'Вход'
    ],
    compact(
        'auth_user',
        'page_content',
        'categories'
    )
));

print($layout_content);
