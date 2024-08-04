<?php
namespace App\Service\Factory;

use App\Service\Provider\SmsProviderInterface;
use App\Service\Provider\EmailProviderInterface;
use App\Service\Provider\PushNotificationProviderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use InvalidArgumentException;

class ProviderFactory
{
    public function __construct(
        private ServiceLocator $smsProviders,
        private ServiceLocator $emailProviders,
        private ServiceLocator $pushNotificationProviders,
        private array $smsProvidersConfig,
        private array $emailProvidersConfig,
        private array $pushNotificationProvidersConfig
    ) {}

    public function createSmsProvider(string $providerName): SmsProviderInterface
    {
        $config = $this->smsProvidersConfig[$providerName] ?? null;
        if ($config && $config['enabled']) {
            if ($this->smsProviders->has($providerName)) {
                return $this->smsProviders->get($providerName);
            }
            throw new InvalidArgumentException("SMS provider service not found: $providerName");
        }

        throw new InvalidArgumentException("SMS provider not enabled: $providerName");
    }

    public function createEmailProvider(string $providerName): EmailProviderInterface
    {
        $config = $this->emailProvidersConfig[$providerName] ?? null;
        if ($config && $config['enabled']) {
            if ($this->emailProviders->has($providerName)) {
                return $this->emailProviders->get($providerName);
            }
            throw new InvalidArgumentException("Email provider service not found: $providerName");
        }

        throw new InvalidArgumentException("Email provider not enabled: $providerName");
    }

    public function createPushNotificationProvider(string $providerName): PushNotificationProviderInterface
    {
        throw new InvalidArgumentException("Push notification provider not enabled: $providerName");
    }

    public function getEmailProviders(): ServiceLocator
    {
        return $this->emailProviders;
    }
    public function getEmailProvidersConfig()
    {
        return $this->emailProvidersConfig;
    }
    public function getSmsProviders(): ServiceLocator
    {
        return $this->smsProviders;
    }
    public function getSmsProvidersConfig()
    {
        return $this->smsProvidersConfig;
    }
}