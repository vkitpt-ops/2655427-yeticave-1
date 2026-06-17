<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $connection */
/** @var array $auth_user */
/** @var array  $categories */

$category_slug = filter_input(INPUT_GET, 'category');

$per_page = 9;
$page = max(1, (int)($_GET['page'] ?? 1));

$total = getLotsCountByCategorySlug($connection, $category_slug);
$pagination = getPaginationData($per_page, $page, $total);
$page = $pagination['page'];

$category_lots = getLotsByCategorySlug($connection, $category_slug, $per_page, $pagination['offset']);
$category_name = getCategoryName($connection, $category_slug);

$page_content = include_template('all-lot.php', array_merge(
    [
        'query' => [
            'category' => $category_slug
        ]
    ],
    compact(
        'category_slug',
        'category_lots',
        'category_name',
        'categories',
        'page',
        'pagination'
    )
));

/** @noinspection PhpPipeOperatorCanBeUsedInspection */
$layout_content = include_template('layout/main.php', array_merge(
    [
        'title' => 'Все лоты'
    ],
    compact(
        'auth_user',
        'page_content',
        'categories',
    )
));

print($layout_content);
