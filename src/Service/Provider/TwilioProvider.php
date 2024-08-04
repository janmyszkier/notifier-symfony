<?php

namespace App\Service\Provider;

use App\Model\NotificationRecipient;

class TwilioProvider implements SmsProviderInterface
{

    public function __construct(
        private string $accountSid,
        private string $authToken,
        private string $phoneNumber
    )
    {
    }

    public function sendSms(NotificationRecipient $recipient, string $message): void
    {
        if(!$recipient->getPhoneNumber()){
            return;
        }
        $client = new \Twilio\Rest\Client($this->accountSid, $this->authToken);
        $client->messages->create(
            $recipient->getPhoneNumber(),
            [
                'from' => $this->phoneNumber,
                'body' => $message,
            ]
        );
    }
}