<?php

namespace App\Controller\Api\Comment;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comments')]
class ReadController extends AbstractController
{
    #[OA\Tag(name: 'Comments')]
    #[Route('/{id}', name: 'api_comments_read', methods: ['GET'])]
    public function __invoke()
    {
        // @TODO
    }
}
