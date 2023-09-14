<?php

namespace App\DTO\Comment;

class CreateResponse
{
    public function __construct(public readonly string $id)
    {
    }
}
