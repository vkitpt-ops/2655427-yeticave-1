<?php

declare(strict_types=1);

require_once __DIR__ . '/init.php';

use enum\HttpStatusCodeEnum;
use enum\HttpMethodEnum;

/** @var mysqli $connection */
/** @var array $auth_user */
/** @var array $categories */

$lot_id = intval(filter_input(INPUT_GET, 'id'));
$lot = getLotById($connection, $lot_id);

if (!$lot) {
    http_response_code(HttpStatusCodeEnum::HttpNotFound->value);
    exit();
}

$bids = getBidsByLotId($connection, $lot_id);
$min_bid = $lot['current_price'] + $lot['bid_step'] ?? 0;
$last_bid = $bids[0] ?? null;

$errors = [];
$form_data = [];

if ($_SERVER['REQUEST_METHOD'] === HttpMethodEnum::POST->value) {
    $form_data = array_map('trim', $_POST);

    validateFormData(VALIDATION_RULES[ADD_BID_FORM_KEY], $form_data, $errors);

    if (empty($errors)) {
        if (intval($form_data['bid_cost'] ?? 0) < $min_bid) {
            $errors['bid_cost'] = "Минимальная ставка: $min_bid";
        }
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $data = prepareBidData($auth_user['id'] ?? 0, $lot_id, $form_data);
        $bid = addBid($connection, $data);

        if ($bid) {
            header("Location: /lot.php?id=" . $lot_id);
            exit;
        }
    }
}

$page_content = include_template('lot.php', compact(
    'auth_user',
    'categories',
    'lot',
    'bids',
    'last_bid',
    'errors',
    'min_bid'
));

/** @noinspection PhpPipeOperatorCanBeUsedInspection */
$layout_content = include_template('layout/main.php', array_merge(
    [
        'title' => $lot['title']
    ],
    compact(
        'auth_user',
        'page_content',
        'categories'
    )
));

print($layout_content);
