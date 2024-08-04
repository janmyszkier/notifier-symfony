<?php

namespace App\Model;

class NotificationRecipient
{
    public function __construct(
        private readonly ?string $email = null,
        private readonly ?string $phoneNumber = null,
        private readonly ?string $deviceToken = null
    )
    {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getDeviceToken(): ?string
    {
        return $this->deviceToken;
    }
}