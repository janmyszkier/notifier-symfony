<?php

namespace App\Service\Provider;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SmtpProvider implements EmailProviderInterface
{
    private $mailer;
    private $host;
    private $port;
    private $username;
    private $password;
    private $encryption;

    public function __construct(
        MailerInterface $mailer,
        string $host,
        int $port,
        string $username,
        string $password,
        string $encryption
    ) {
        $this->mailer = $mailer;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->encryption = $encryption;
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        /* symfony mailer? - to be installed, then handle this here  */
        $email = (new Email())
            ->from('dev@codingmice.com')
            ->to($to)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}