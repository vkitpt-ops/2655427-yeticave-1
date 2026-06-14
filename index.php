<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/getwinner.php';

/** @var mysqli $connection */
/** @var array $auth_user */
/** @var array  $categories */

$lots = getNewLots($connection);

$page_content = include_template('index.php', compact(
    'categories',
    'lots'
));

/** @noinspection PhpPipeOperatorCanBeUsedInspection */
$layout_content = include_template('layout/main.php', array_merge(
    [
        'title' => 'Главная'
    ],
    compact(
        'auth_user',
        'page_content',
        'categories'
    )
));

print($layout_content);
