<?php

namespace App\Service\Provider;

interface SmsProviderInterface
{
    public function sendSms(string $to, string $message): void;
}