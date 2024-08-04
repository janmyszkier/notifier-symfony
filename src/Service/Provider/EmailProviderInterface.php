<?php

namespace App\Service\Provider;

use App\Model\NotificationRecipient;

interface EmailProviderInterface
{
    public function sendEmail(NotificationRecipient $recipient, string $subject, string $body): void;
}