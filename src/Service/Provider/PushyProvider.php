<?php

namespace App\Service\Provider;

use Jlorente\Pushy\Pushy;

class PushyProvider implements PushNotificationProviderInterface
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function sendPushNotification(string $to, string $message) :void
    {
         $client = new Pushy($this->apiKey);
         $client->api()->sendNotifications([
             'to' => $to,
             'data' => $message
         ]);
    }
}