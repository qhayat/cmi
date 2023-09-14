<?php

namespace App\DTO\Authentication;

class AuthenticatedResponse
{
    public function __construct(
        public readonly string $token
    ) {
    }
}
