<?php

namespace App\DTO\Collection;

class Page
{
    public function __construct(
        public readonly int $total,
        public readonly int $current,
        public readonly int $size,
    ) {
    }
}
