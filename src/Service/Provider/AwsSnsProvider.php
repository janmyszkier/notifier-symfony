<?php

namespace App\Service\Provider;

use App\Model\NotificationRecipient;

class AwsSnsProvider implements SmsProviderInterface
{
    private $apiKey;
    private $apiSecret;
    private $region;

    /**
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $region - @TODO: make this env based
     */
    public function __construct(string $apiKey, string $apiSecret, string $region = 'eu-north-1')
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->region = $region;
    }

    public function sendSms(NotificationRecipient $recipient, string $message): void
    {
        if(!$recipient->getPhoneNumber()){
            return;
        }
         $client = new \Aws\Sns\SnsClient([
             'version' => 'latest',
             'region'  => $this->region,
             'credentials' => [
                 'key'    => $this->apiKey,
                 'secret' => $this->apiSecret,
             ],
         ]);
         $client->publish([
             'Message' => $message,
             'PhoneNumber' => $recipient->getPhoneNumber(),
         ]);
    }
}