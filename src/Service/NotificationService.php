<?php

namespace App\Service;

use App\Service\Factory\ProviderFactory;
use App\Service\Provider\EmailProviderInterface;
use App\Service\Provider\PushNotificationProviderInterface;
use App\Service\Provider\SmsProviderInterface;

class NotificationService
{
    /* make an interface and inject it here */
    private ProviderFactory $providerFactory;

    private int $maxAttempts;

    private bool $throttlingEnabled;

    private int $throttlingLimit;

    /**
     * @var int Throttling period in seconds
     */
    private int $throttlingPeriod;

    private bool $trackingEnabled;
    private int $attempts = 0;

    public function __construct(
        ProviderFactory $providerFactory,
        int $maxAttempts,
        bool $throttlingEnabled,
        int $throttlingLimit,
        int $throttlingPeriod,
        bool $trackingEnabled
    )
    {
        $this->providerFactory = $providerFactory;
        $this->maxAttempts = $maxAttempts;
        $this->throttlingEnabled = $throttlingEnabled;
        $this->throttlingLimit = $throttlingLimit;
        $this->throttlingPeriod = $throttlingPeriod;
        $this->trackingEnabled = $trackingEnabled;
    }

    public function sendSms(string $to, string $message, string $userId)
    {

    }

    public function sendEmail(string $to, string $subject, string $body, string $userId)
    {
        foreach ($this->providerFactory->getEmailProviders() as $providerName => $config) {
            $config = $this->providerFactory->getEmailProvidersConfig();

            if (!$config[$providerName]['enabled']) {
                continue;
            }

            try {
                if ($this->providerFactory->getEmailProviders()->has($providerName)) {
                    $emailProvider = $this->providerFactory->createEmailProvider($providerName);
                    try {
                        $emailProvider->sendEmail($to, $subject, $body);
                        var_dump('message sent');
                    } catch (\Exception $e) {

                        var_dump($e->getMessage());
                        var_dump($e->getTraceAsString());
                    }
                    return true;
                }
            } catch (\Exception $e) {
                var_dump("Failed to send email via $providerName: " . $e->getMessage());
            }
        }


    }

    public function sendPushNotification(string $to, string $message, string $userId)
    {

    }

    public function sendNotification(string $type,string $to,string $content,string $userId)
    {

    }

}