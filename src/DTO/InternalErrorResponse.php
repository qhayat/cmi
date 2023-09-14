<?php

namespace App\DTO;

class InternalErrorResponse
{
    public function __construct(
        public readonly string $message
    ) {
    }
}
