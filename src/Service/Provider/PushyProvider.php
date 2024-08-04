<?php

namespace App\Service\Provider;

use App\Model\NotificationRecipient;
use Jlorente\Pushy\Pushy;

class PushyProvider implements PushNotificationProviderInterface
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function sendPushNotification(NotificationRecipient $recipient, string $message) :void
    {
        if(!$recipient->getDeviceToken()){
            return;
        }
         $client = new Pushy($this->apiKey);
         $client->api()->sendNotifications([
             'to' => $recipient->getDeviceToken(),
             'data' => $message
         ]);
    }
}