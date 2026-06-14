<?php

declare(strict_types=1);

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions/database/core.php';
require_once __DIR__ . '/functions/database/query/bid.php';
require_once __DIR__ . '/functions/helpers.php';
require_once __DIR__ . '/functions/functions.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$con = connectToMySQL();

$dsn = sprintf(
    'smtp://%s:%s@smtp.mail.ru:2525?encryption=tls&auth_mode=login',
    $_ENV['MAIL_USER'],
    $_ENV['MAIL_PASSWORD']
);
$transport = Transport::fromDsn($dsn);

assignWinnerBids($con);

$winner_bids = getWinnerBids($con);
$mailer = new Mailer($transport);

foreach ($winner_bids as $winner_bid) {

    $email_content = include_template(
        'email.php',
        [
            'winner_name' => $winner_bid['name'],
            'lot_name'    => $winner_bid['title'],
            'lot_url'     => '/lot.php?id=' . $winner_bid['lot_id'],
            'bets_url'    => '/my-bets.php'
        ]
    );

    $email = (new Email())
        ->from('mmmtttrrt@mail.ru')
        ->to($winner_bid['email'])
        ->subject('Ваша ставка победила')
        ->html($email_content);

    try {
        $mailer->send($email);
        setWinnerNotified($con, (int)$winner_bid['lot_id']);
    } catch (\Throwable $e) {
        echo $e->getMessage();
    }
}
