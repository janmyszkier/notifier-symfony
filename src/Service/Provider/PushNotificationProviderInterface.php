<?php

namespace App\Service\Provider;

use App\Model\NotificationRecipient;

interface PushNotificationProviderInterface
{
    public function sendPushNotification(NotificationRecipient $recipient, string $message): void;
}