<?php

namespace App\Controller\Api\Authentication;

use App\DTO\Authentication\AuthenticatedResponse;
use App\DTO\Authentication\CredentialsRequest;
use App\DTO\Authentication\UnauthorizedResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class LoginController extends AbstractController
{
    #[OA\Tag(name: 'Authentication')]
    #[OA\RequestBody(
        content: new Model(
            type: CredentialsRequest::class
        ),
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Success response',
        content: new Model(
            type: AuthenticatedResponse::class
        )
    )]
    #[OA\Response(
        response: Response::HTTP_UNAUTHORIZED,
        description: 'Unauthorized',
        content: new Model(
            type: UnauthorizedResponse::class
        )
    )]
    #[Route('/login', name: 'api_authentication_login', methods: ['POST'])]
    public function __invoke()
    {
    }
}
