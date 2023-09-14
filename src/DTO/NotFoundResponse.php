<?php

namespace App\DTO;

class NotFoundResponse
{
    public function __construct(
        public readonly string $message
    ) {
    }
}
