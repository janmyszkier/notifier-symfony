<?php

namespace App\Service\Provider;

interface EmailProviderInterface
{
    public function sendEmail(string $to, string $subject, string $body): void;
}