<?php

namespace App\DTO\Comment;

use Symfony\Component\Validator\Constraints as Assert;

class CreateRequest
{
    public const UUID_REGEX = '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/';

    public function __construct(
        #[Assert\NotBlank(message: 'The body cannot be empty')]
        public string $body,

        #[Assert\Regex(self::UUID_REGEX, message: 'The parent id is not valid')]
        public ?string $parentId = null,
    ) {
    }
}
