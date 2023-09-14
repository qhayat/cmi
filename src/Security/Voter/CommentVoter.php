<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    public const DELETE = 'COMMENT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::DELETE])
            && $subject instanceof Comment;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        /**
         * @var Comment $comment
         */
        $comment = $subject;
        switch ($attribute) {
            case self::DELETE:
                return $user->getId() === $comment->getAuthor()->getId();
                break;
        }

        return false;
    }
}
