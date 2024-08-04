<?php

namespace App\Service\Provider;

use App\Model\NotificationRecipient;

interface SmsProviderInterface
{
    public function sendSms(NotificationRecipient $recipient, string $message): void;
}