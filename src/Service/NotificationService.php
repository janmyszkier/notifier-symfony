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
        $emailProviders = $this->providerFactory->getSmsProviders();
        $smsConfig = $this->providerFactory->getSmsProvidersConfig();

        foreach ($emailProviders as $providerName => $config) {

            if (!$smsConfig[$providerName]['enabled']) {
                /* Skip disabled provider */
                continue;
            }

            $smsProvider = $this->providerFactory->createSmsProvider($providerName);
            try {
                $smsProvider->sendSms($to, $message);
            } catch (\Exception $e) {
                /* @FIXME: add logger later */
            }
            return true;
        }

        return false;
    }

    public function sendEmail(string $to, string $subject, string $body, string $userId)
    {
        $emailProviders = $this->providerFactory->getEmailProviders();
        foreach ($emailProviders as $providerName => $config) {
            $config = $this->providerFactory->getEmailProvidersConfig();

            if (!$config[$providerName]['enabled']) {
                /* Skip disabled provider */
                continue;
            }

            $emailProvider = $this->providerFactory->createEmailProvider($providerName);
            try {
                $emailProvider->sendEmail($to, $subject, $body);
            } catch (\Exception $e) {
                /* @FIXME: add logger later */
            }
            return true;
        }

        return false;
    }

    public function sendPushNotification(string $to, string $message, string $userId)
    {

    }

    public function sendNotification(string $type,string $to,string $content,string $userId)
    {

    }

}