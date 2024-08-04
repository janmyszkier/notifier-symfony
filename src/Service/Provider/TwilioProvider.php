<?php

namespace App\Service\Provider;

class TwilioProvider implements SmsProviderInterface
{

    public function __construct(
        private string $accountSid,
        private string $authToken,
        private string $phoneNumber
    )
    {
    }

    public function sendSms(string $to, string $message): void
    {
        $client = new \Twilio\Rest\Client($this->accountSid, $this->authToken);
        $client->messages->create(
            $to,
            [
                'from' => $this->phoneNumber,
                'body' => $message,
            ]
        );
    }
}