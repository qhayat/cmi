<?php

namespace App\Controller\Api\Comment;

use App\DTO\Authentication\UnauthorizedResponse;
use App\DTO\InternalErrorResponse;
use App\DTO\NotFoundResponse;
use App\Repository\CommentRepository;
use App\Security\Voter\CommentVoter;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comments')]
class DeleteController extends AbstractController
{
    public function __construct(
        private readonly CommentRepository $repository,
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger
    ) {
    }

    #[OA\Tag(name: 'Comments')]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'Comment deleted'
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Comment not found',
        content: new Model(
            type: NotFoundResponse::class
        )
    )]
    #[OA\Response(
        response: Response::HTTP_UNAUTHORIZED,
        description: 'Unauthorized',
        content: new Model(
            type: UnauthorizedResponse::class
        )
    )]
    #[OA\Response(
        response: Response::HTTP_INTERNAL_SERVER_ERROR,
        description: 'An internal error occurred',
        content: new Model(
            type: InternalErrorResponse::class
        )
    )]
    #[Route('/{id}', name: 'api_comments_delete', methods: ['DELETE'])]
    public function __invoke(string $id)
    {
        try {
            if (null === $comment = $this->repository->find($id)) {
                return $this->json(
                    new NotFoundResponse(sprintf('Comment %s not found', $id)),
                    Response::HTTP_NOT_FOUND
                );
            }

            if (!$this->isGranted(CommentVoter::DELETE, $comment)) {
                return $this->json(
                    new UnauthorizedResponse('Unauthorized'),
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $this->em->remove($comment);
            $this->em->flush();

            return new Response('', Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'message' => $e->getMessage(),
            ]);

            return $this->json(
                new InternalErrorResponse('An internal error occurred!'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
