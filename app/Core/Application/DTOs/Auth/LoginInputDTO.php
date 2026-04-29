<?php

namespace App\Core\Application\DTOs\Auth;

readonly class LoginInputDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $deviceName = 'web-api'
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            $data['email'],
            $data['password'],
            $data['device_name'] ?? 'web-api'
        );
    }
}
