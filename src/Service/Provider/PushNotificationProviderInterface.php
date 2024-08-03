<?php

namespace App\Service\Provider;

interface PushNotificationProviderInterface
{
    public function sendPushNotification(string $to, string $message): void;
}