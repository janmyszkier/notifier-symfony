<?php

namespace App\Service\Provider;

class AwsSesProvider implements EmailProviderInterface
{
    private $apiKey;
    private $apiSecret;
    private $region;

    public function __construct(string $apiKey, string $apiSecret, string $region = 'eu-north-1')
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->region = $region;
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
         $client = new \Aws\Ses\SesClient([
             'version' => 'latest',
             'region'  => $this->region,
             'credentials' => [
                 'key'    => $this->apiKey,
                 'secret' => $this->apiSecret,
             ],
         ]);
         $client->sendEmail([
             /* @TODO: make this env-based */
             'Source' => 'dev@codingmice.com',
             'Destination' => [
                 'ToAddresses' => [$to],
             ],
             'Message' => [
                 'Subject' => [
                     'Data' => $subject,
                 ],
                 'Body' => [
                     'Text' => [
                         'Data' => $body,
                     ],
                 ],
             ],
         ]);
    }
}