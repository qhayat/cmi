<?php

namespace App\Controller\Api\Comment;

use App\DTO\CollectionResponse;
use App\DTO\InternalErrorResponse;
use App\Http\QueryParser\FilterQueryParser;
use App\Http\QueryParser\PageQueryParser;
use App\Http\QueryParser\SortQueryParser;
use App\Repository\RepositoryInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/comments')]
class CollectionController extends AbstractController
{
    public function __construct(
        private readonly RepositoryInterface $commentRepository,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[OA\Tag(name: 'Comments')]
    #[OA\Response(
        response: Response::HTTP_INTERNAL_SERVER_ERROR,
        description: 'An internal error occurred',
        content: new Model(
            type: InternalErrorResponse::class
        )
    )]
    #[Route('', name: 'api_comments_collection', methods: ['GET'])]
    public function __invoke(
        PageQueryParser $pageQueryParserCommentCollection,
        SortQueryParser $sortQueryParserCommentCollection,
        FilterQueryParser $filterQueryParserCommentCollection
    ) {
        try {
            $data = json_decode($this->serializer->serialize(
                $this->commentRepository->paginate(
                    $pageQueryParserCommentCollection,
                    $sortQueryParserCommentCollection,
                    $filterQueryParserCommentCollection
                ),
                'json',
                ['groups' => ['comment:collection:read']]
            ));

            return $this->json(
                new CollectionResponse(
                    $data,
                    $this->commentRepository->total($filterQueryParserCommentCollection),
                    $pageQueryParserCommentCollection,
                ),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->json(
                new InternalErrorResponse('An internal error occurred!'),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
