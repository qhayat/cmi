<?php

namespace App\DTO;

class BadRequestResponse
{
    public function __construct(
        public readonly string $message
    ) {
    }
}
