<?php

namespace Tests\Unit\Controller\Api\Comment;

use App\Controller\Api\Comment\CollectionController;
use App\Http\QueryParser\FilterQueryParser;
use App\Http\QueryParser\PageQueryParser;
use App\Http\QueryParser\SortQueryParser;
use App\Repository\CommentRepository;
use App\Repository\RepositoryInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CollectionControllerTest extends TestCase
{
    private RepositoryInterface $commentRepository;
    private SerializerInterface $serializer;
    private LoggerInterface $logger;

    public function setUp(): void
    {
        $this->commentRepository = $this->createMock(CommentRepository::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testInternalErrorResponse(): void
    {
        $this->commentRepository
            ->expects($this->once())
            ->method('paginate')
            ->will($this->throwException(new \Exception()));

        $this->commentRepository
            ->expects($this->never())
            ->method('total');

        $this->logger
            ->expects($this->once())
            ->method('error');

        $controller = $this->getController();
        $result = $controller(
            new PageQueryParser($this->requestStack(), 10),
            new SortQueryParser($this->requestStack(), []),
            new FilterQueryParser($this->requestStack(), []),
        );

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $result->getStatusCode());
        $this->assertEquals('{"message":"An internal error occurred!"}', $result->getContent());
    }

    public function testSuccess(): void
    {
        $comments = '[{"id":"007bd38f-c932-4703-884f-424c4a824445","body":"test","author":{"id":"36bc0bee-218a-4dc6-9109-eb29c0149839","firstName":"John","lastName":"DOE"},"comments":[],"createdAt":"2023-09-07T11:56:59+00:00","updatedAt":"2023-09-07T11:56:59+00:00"},{"id":"75ea7c4e-3d3e-495e-a2a8-a165ba2947eb","body":"test","author":{"id":"36bc0bee-218a-4dc6-9109-eb29c0149839","firstName":"John","lastName":"DOE"},"comments":[],"createdAt":"2023-09-07T11:57:10+00:00","updatedAt":"2023-09-07T11:57:10+00:00"}]';

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->willReturn($comments);

        $this->commentRepository
            ->expects($this->once())
            ->method('paginate');

        $this->commentRepository
            ->expects($this->once())
            ->method('total')
            ->willReturn(2);

        $this->logger
            ->expects($this->never())
            ->method('error');

        $controller = $this->getController();
        $result = $controller(
            new PageQueryParser($this->requestStack(), 10),
            new SortQueryParser($this->requestStack(), []),
            new FilterQueryParser($this->requestStack(), []),
        );

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(Response::HTTP_OK, $result->getStatusCode());
        $this->assertEquals(
            '{"meta":{"totalItems":2,"page":{"total":1,"current":1,"size":10}},"data":[{"id":"007bd38f-c932-4703-884f-424c4a824445","body":"test","author":{"id":"36bc0bee-218a-4dc6-9109-eb29c0149839","firstName":"John","lastName":"DOE"},"comments":[],"createdAt":"2023-09-07T11:56:59+00:00","updatedAt":"2023-09-07T11:56:59+00:00"},{"id":"75ea7c4e-3d3e-495e-a2a8-a165ba2947eb","body":"test","author":{"id":"36bc0bee-218a-4dc6-9109-eb29c0149839","firstName":"John","lastName":"DOE"},"comments":[],"createdAt":"2023-09-07T11:57:10+00:00","updatedAt":"2023-09-07T11:57:10+00:00"}]}',
            $result->getContent(),
        );
    }

    private function requestStack(): RequestStack
    {
        $request = Request::createFromGlobals();

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack
            ->method('getCurrentRequest')
            ->willReturn($request);

        return $requestStack;
    }

    private function getController(): CollectionController
    {
        $controller = (new CollectionController(
            $this->commentRepository,
            $this->serializer,
            $this->logger
        ));

        $controller->setContainer($this->createMock(ContainerInterface::class));

        return $controller;
    }
}
