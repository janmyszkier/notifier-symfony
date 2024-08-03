<?php

namespace App\Service;

use App\Service\Provider\EmailProviderInterface;
use App\Service\Provider\PushNotificationProviderInterface;
use App\Service\Provider\SmsProviderInterface;

class NotificationService
{
    /* @var SmsProviderInterface[] $smsProviders */
    private array $smsProviders = [];

    /* @var EmailProviderInterface[] $emailProviders */
    private array $emailProviders = [];

    /* @var PushNotificationProviderInterface[] $pushNotificationProviders */
    private array $pushNotificationProviders = [];

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
        array $smsProviders,
        array $emailProviders,
        array $pushNotificationProviders,
        bool $failover,
        int $maxAttempts,
        bool $throttlingEnabled,
        int $throttlingLimit,
        int $throttlingPeriod,
        bool $trackingEnabled
    )
    {
        $this->smsProviders = $smsProviders;
        $this->emailProviders = $emailProviders;
        $this->pushNotificationProviders = $pushNotificationProviders;
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

    public function sendEmail(string $to, string $subject, string $body, string $userId)
    {

    }

    public function sendPushNotification(string $to, string $message, string $userId)
    {

    }

    public function sendNotification(string $type,string $to,string $content,string $userId)
    {

    }

}