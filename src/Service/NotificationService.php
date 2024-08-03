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

    private bool $failover;

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
        bool $failover,
        int $maxAttempts,
        bool $throttlingEnabled,
        int $throttlingLimit,
        int $throttlingPeriod,
        bool $trackingEnabled
    )
    {
        $this->providerFactory = $providerFactory;
        $this->failover = $failover;
        $this->maxAttempts = $maxAttempts;
        $this->throttlingEnabled = $throttlingEnabled;
        $this->throttlingLimit = $throttlingLimit;
        $this->throttlingPeriod = $throttlingPeriod;
        $this->trackingEnabled = $trackingEnabled;
    }

    public function sendSms(string $to, string $message, string $userId)
    {

    }

    public function sendEmail(string $providerName, string $to, string $subject, string $body, string $userId)
    {
        $emailProvider = $this->providerFactory->createEmailProvider($providerName);

        /* @FIXME: inject logger and use it */
        try {
            $emailProvider->sendEmail($to, $subject, $body);
            var_dump('message sent');
        } catch (\Exception $e) {

            var_dump($e->getMessage());
            var_dump($e->getTraceAsString());
            // Handle failover and retry logic
        }
    }

    public function sendPushNotification(string $to, string $message, string $userId)
    {

    }

    public function sendNotification(string $type,string $to,string $content,string $userId)
    {

    }

}