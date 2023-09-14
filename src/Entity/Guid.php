<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

trait Guid
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue('CUSTOM')]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    protected ?UuidInterface $id;

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }
}
