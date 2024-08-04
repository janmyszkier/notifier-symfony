<?php

namespace App\Service;

use App\Model\NotificationRecipient;
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

    public function sendSms(NotificationRecipient $recipient, string $message, string $userId): bool
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
                $smsProvider->sendSms($recipient, $message);
            } catch (\Exception $e) {
                /* @FIXME: add logger later */
            }
            return true;
        }

        return false;
    }

    public function sendEmail(NotificationRecipient $recipient, string $subject, string $body, string $userId): bool
    {
        $emailProviders = $this->providerFactory->getEmailProviders();
        $emailProvidersConfig = $this->providerFactory->getEmailProvidersConfig();
        foreach ($emailProviders as $providerName => $config) {

            if (!$emailProvidersConfig[$providerName]['enabled']) {
                /* Skip disabled provider */
                continue;
            }

            $emailProvider = $this->providerFactory->createEmailProvider($providerName);
            try {
                $emailProvider->sendEmail($recipient, $subject, $body);
            } catch (\Exception $e) {
                /* @FIXME: add logger later */
            }
            return true;
        }

        return false;
    }

    public function sendPushNotification(NotificationRecipient $recipient, string $message, string $userId): bool
    {
        $pushNotificationProviders = $this->providerFactory->getPushNotificationProviders();
        $pushNotificationProvidersConfig = $this->providerFactory->getPushNotificationProvidersConfig();
        foreach ($pushNotificationProviders as $providerName => $config) {

            if (!$pushNotificationProvidersConfig[$providerName]['enabled']) {
                /* Skip disabled provider */
                continue;
            }

            $pushNotificationProvider = $this->providerFactory->createPushNotificationProvider($providerName);
            try {
                $pushNotificationProvider->sendPushNotification($recipient, $message);
            } catch (\Exception $e) {
                /* @FIXME: add logger later */
            }
            return true;
        }

        return false;
    }

    public function sendNotification(NotificationRecipient $recipient, ?string $subject, string $body, string $userId, array $channels): void
    {
        foreach ($channels as $channel) {
            $providersLocator = null;
            $providersConfig = null;
            $messageSendViaChannel = false;

            switch ($channel) {
                case 'email':
                    $providersLocator = $this->providerFactory->getEmailProviders();
                    $providersConfig = $this->providerFactory->getEmailProvidersConfig();
                    break;
                case 'sms':
                    $providersLocator = $this->providerFactory->getSmsProviders();
                    $providersConfig = $this->providerFactory->getSmsProvidersConfig();
                    break;
                case 'push_notification':
                    $providersLocator = $this->providerFactory->getPushNotificationProviders();
                    $providersConfig = $this->providerFactory->getPushNotificationProvidersConfig();
                    break;
            }

            if (!$providersLocator || !$providersConfig) {
                continue;
            }

            foreach ($providersConfig as $providerName => $config) {
                if($messageSendViaChannel === true){
                    continue;
                }
                if (!$providersConfig[$providerName]['enabled']) {
                    continue;
                }

                try {
                    if ($providersLocator->has($providerName)) {
                        $provider = $providersLocator->get($providerName);

                        match($channel){
                            'sms' => $provider->sendSms($recipient, $body),
                            'email' => $provider->sendEmail($recipient, $subject, $body),
                            'push_notification' => $provider->sendPushNotification($recipient, $body)
                        };
                        $messageSendViaChannel = true;
                        var_dump("Sent notification via $providerName for channel $channel: ");
                    }
                } catch (\Exception $e) {
                    $messageSendViaChannel = false;
                    var_dump("Failed to send notification via $providerName for channel $channel: " . $e->getMessage());
                }
            }

        }

    }

}