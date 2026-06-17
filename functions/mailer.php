<?php

declare(strict_types=1);

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Sends an email message.
 *
 * Creates and sends an email using the configured mail transport.
 *
 * @param string $dsn Mail transport DSN
 * @param string $to Recipient email address
 * @param string $from Sender email address
 * @param string $subject Email subject
 * @param string $body Email HTML content
 *
 * @throws TransportExceptionInterface If sending the email fails
 *
 * @return void
 */
function sendEmail(string $dsn, string $to, string $from, string $subject, string $body): void
{
    $transport = Transport::fromDsn($dsn);

    $email = new Email();
    $email->to($to);
    $email->from($from);
    $email->subject($subject);
    $email->html($body);

    $mailer = new Mailer($transport);
    $mailer->send($email);
}

/**
 * Builds a SMTP DSN string from configuration data.
 *
 * Creates a connection string required by the mail transport.
 *
 * @param array $smtp SMTP configuration parameters
 *
 * @return string SMTP DSN
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
        urlencode($user),
        urlencode($password),
        $host,
        $port
    );
}
