<?php

declare(strict_types=1);

/** @var mysqli $connection */

require_once __DIR__ . '/functions/mailer.php';

$smtp = require __DIR__ . '/config/smtp.php';

$dsn = buildDsn($smtp);

assignWinnerBids($connection);

$winner_bids = getWinnerBids($connection);

foreach ($winner_bids as $winner_bid) {

    $lot_id = (int) ($winner_bid['lot_id'] ?? 0);
    $lot_name = $winner_bid['title'] ?? '';
    $winner_name = $winner_bid['name'] ?? '';
    $winner_email = $winner_bid['email'] ?? '';
    $yeticave_url = getenv('APP_URL') ?: 'http://localhost';

    if ($lot_id > 0 && filter_var($winner_email, FILTER_VALIDATE_EMAIL)) {
        $email_content = include_template(
            'email.php',
            [
                'winner_name' => $winner_name,
                'lot_name'    => $lot_name,
                'lot_url'     => $yeticave_url . '/lot.php?id=' . $lot_id,
                'bets_url'    => $yeticave_url . '/my-bets.php',
            ]
        );

        try {
            sendEmail(
                $dsn,
                $winner_email,
                'keks@phpdemo.ru',
                'Ваша ставка победила',
                $email_content
            );
            setWinnerNotified($connection, $lot_id);
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}
