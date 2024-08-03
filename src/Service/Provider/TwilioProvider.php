<?php

namespace App\Service\Provider;

class TwilioProvider implements SmsProviderInterface
{
    private $accountSid;
    private $authToken;

    public function __construct(string $accountSid, string $authToken)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
    }

    public function sendSms(string $to, string $message): void
    {
        $client = new \Twilio\Rest\Client($this->accountSid, $this->authToken);
        $client->messages->create(
            $to,
            [
                /* @TODO: make this env based */
                'from' => '+48505396591',
                'body' => $message,
            ]
        );
    }
}