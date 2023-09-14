<?php

namespace App\Controller\Api\Comment;

use App\DTO\Comment\CreateRequest as CommentCreateRequest;
use App\DTO\Comment\CreateResponse as CommentCreateResponse;
use App\DTO\InternalErrorResponse;
use App\DTO\NotFoundResponse;
use App\Factory\Comment\CreateFromRequest as CommentCreateFromRequest;
use App\Repository\RepositoryInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/comments')]
class CreateController extends AbstractController
{
    public function __construct(
        private readonly RepositoryInterface $commentRepository,
        private readonly RepositoryInterface $userRepository,
        private readonly CommentCreateFromRequest $commentCreateFromRequest,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[OA\Tag(name: 'Comments')]
    #[OA\RequestBody(
        content: new Model(
            type: CommentCreateRequest::class
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Success response',
        content: new Model(
            type: CommentCreateResponse::class
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'An associated resource not found',
        content: new Model(
            type: NotFoundResponse::class
        )
    )]
    #[OA\Response(
        response: Response::HTTP_INTERNAL_SERVER_ERROR,
        description: 'An internal error occurred',
        content: new Model(
            type: InternalErrorResponse::class
        )
    )]
    #[Route('', name: 'api_comments_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CommentCreateRequest $request)
    {
        try {
            $parent = null;
            if (null !== $request->parentId && null === $parent = $this->commentRepository->find($request->parentId)) {
                return $this->json(
                    new NotFoundResponse(sprintf('Parent comment with id %s not found!',
                        $request->parentId)), Response::HTTP_NOT_FOUND
                );
            }

            $comment = $this->commentCreateFromRequest->create($request, $this->getUser(), $parent);

            return $this->json(
                new CommentCreateResponse($comment->getId())
            );
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'message' => $e->getMessage(),
            ]);

            return $this->json(
                new InternalErrorResponse('An internal error occurred'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
