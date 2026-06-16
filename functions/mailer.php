<?php

declare(strict_types=1);

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
* Sends an email message
*
* @param string $dsn
* @param string $to
* @param string $from
* @param string $subject
* @param string $body
* @throws TransportExceptionInterface
*/
function sendEmail(string $dsn, string $to, string $from, string $subject, string $body ): void
{
    $transport = Transport:: fromDsn ($dsn) ;

    $email = new Email();
    $email->to($to);
    $email->from($from);
    $email->subject($subject);
    $email->html($body);

    $mailer = new Mailer($transport);
    $mailer->send($email);
}

/**
* Builds SMTP DSN from config
*
* @param array $smtp
*
* @return string
*/
function buildDsn(array $smtp): string
{
    $host = $smtp['host'] ?? '';
    $port = (int) ($smtp['port'] ?? 0);
    $user = $smtp['user'] ?? '';
    $password = $smtp['password'] ?? '';

    if ($host === '' || $port <= 0 || $user === '' || $password === '') {
        exit('Не заданы параметры SMTP');
    }

    return sprintf(
        'smtp://%s:%s@%s:%d?encryption=tls&auth_mode=login',
        urlencode ($user),
        urlencode ($password) ,
        $host,
        $port
    );
}
