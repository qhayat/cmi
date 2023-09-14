<?php

namespace App\DTO\Authentication;

use Symfony\Component\Validator\Constraints as Assert;

class CredentialsRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Please provide your email. ')]
        #[Assert\Email(message: 'Please provide a valid email. ')]
        public ?string $email = null,

        #[Assert\NotNull(message: 'Please provide your password. ')]
        public ?string $password = null,
    ) {
    }
}
