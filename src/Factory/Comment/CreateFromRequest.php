<?php

namespace App\Factory\Comment;

use App\DTO\Comment\CreateRequest;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CreateFromRequest
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function create(CreateRequest $request, User $user, Comment $parent = null): Comment
    {
        $comment = (new Comment())
            ->setBody($request->body)
            ->setAuthor($user)
            ->setParent($parent);

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}
