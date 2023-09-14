<?php

namespace App\Security;

use App\DTO\Authentication\CredentialsRequest;
use App\DTO\Authentication\UnauthorizedResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'api_authentication_login';

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private JWTTokenManagerInterface $JWTManager,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        /**
         * @var CredentialsRequest $credential
         */
        $credential = $this->getCredentials($request);
        $request->getSession()->set(Security::LAST_USERNAME, $credential->email);

        return new Passport(
            new UserBadge($credential->email),
            new PasswordCredentials((string) $credential->password),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new JsonResponse([
            'token' => $this->JWTManager->create($token->getUser()),
        ], Response::HTTP_OK);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse(new UnauthorizedResponse('Invalid username or password'), Response::HTTP_UNAUTHORIZED);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    protected function getCredentials(Request $request): CredentialsRequest
    {
        $credential = $this->serializer->deserialize($request->getContent(), CredentialsRequest::class, 'json');
        $errors = $this->validator->validate($credential);

        if (count($errors)) {
            $message = '';
            foreach ($errors as $error) {
                $message .= $error->getMessage();
            }

            throw new BadRequestHttpException($message);
        }

        return $credential;
    }
}
