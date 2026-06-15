<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

/** @var mysqli $connection */
/** @var array $auth_user */
/** @var array $categories */

$bids = getBidsByUserId($connection, $auth_user['id']);
$winner_bid = getWinnerBidIds($connection, $auth_user['id']);

$page_content = include_template('my-bets.php', compact(
    'auth_user',
    'categories',
    'bids',
    'winner_bid'
));

/** @noinspection PhpPipeOperatorCanBeUsedInspection */
$layout_content = include_template('layout/main.php', array_merge(
    [
        'title' => 'Мои ставки'
    ],
    compact(
        'auth_user',
        'page_content',
        'categories'
    )
));

print($layout_content);
