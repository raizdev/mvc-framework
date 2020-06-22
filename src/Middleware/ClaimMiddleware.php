<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Middleware;

use App\Service\TokenService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use InvalidArgumentException;

/**
 * Class ClaimMiddleware
 *
 * @package App\Middleware
 */
class ClaimMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $responseFactory;

    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * ClaimMiddleware constructor.
     *
     * @param TokenService             $tokenService
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        TokenService $tokenService,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->tokenService    = $tokenService;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Invoke middleware.
     *
     * @param   ServerRequestInterface   $request  The request
     * @param   RequestHandlerInterface  $handler  The handler
     *
     * @return ResponseInterface The response
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $authorization = explode(' ', (string)$request->getHeaderLine('Authorization'));
        $type          = $authorization[0] ?? '';
        $credentials   = $authorization[1] ?? '';

        try {
            if ($type === 'Bearer' && $this->tokenService->validateToken($credentials)) {
                // Append valid token
                $parsedToken = $this->tokenService->createParsedToken($credentials);
                $request     = $request->withAttribute('token', $parsedToken);

                // Append the user id as request attribute
                $request = $request->withAttribute('ares_uid', $parsedToken->getClaim('uid'));
            }
        } catch (InvalidArgumentException $e) {
            return $this->responseFactory->createResponse()
                ->withHeader('Access-Control-Allow-Origin', getenv('WEB_FRONTEND_LINK'))
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }

        return $handler->handle($request);
    }
}
