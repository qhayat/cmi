<?php

namespace App\DTO\Authentication;

class UnauthorizedResponse
{
    public function __construct(
        public readonly string $message
    ) {
    }
}
