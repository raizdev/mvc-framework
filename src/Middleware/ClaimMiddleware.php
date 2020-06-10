<?php

namespace App\Middleware;

use App\Service\TokenService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * ClaimMiddleware.
 */
class ClaimMiddleware implements MiddlewareInterface
{
    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * The constructor.
     *
     * @param   TokenService  $tokenService
     */
    public function __construct(
        TokenService $tokenService
    ) {
        $this->tokenService = $tokenService;
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

        if ($type === 'Bearer' && $this->tokenService->validateToken($credentials)) {
            // Append valid token
            $parsedToken = $this->tokenService->createParsedToken($credentials);
            $request     = $request->withAttribute('token', $parsedToken);

            // Append the user id as request attribute
            $request = $request->withAttribute('uid', $parsedToken->getClaim('uid'));
        }

        return $handler->handle($request);
    }
}