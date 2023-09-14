<?php

namespace App\DTO\Collection;

class Meta
{
    public function __construct(
        public readonly int $totalItems,
        public readonly Page $page,
    ) {
    }
}
