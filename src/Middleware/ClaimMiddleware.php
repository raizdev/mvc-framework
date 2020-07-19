<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use InvalidArgumentException;
use ReallySimpleJWT\Token;

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
     * ClaimMiddleware constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory
    ) {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param ServerRequestInterface  $request The request
     * @param RequestHandlerInterface $handler The handler
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
        $secret        = $_ENV['TOKEN_SECRET'];

        try {
            if ($type === 'Bearer' && Token::validate($credentials, $secret)) {
                // Append valid token
                $parsedToken = Token::parser($credentials, $secret);
                $request     = $request->withAttribute('token', $parsedToken);

                // Append the user id as request attribute
                $request = $request->withAttribute('ares_uid', Token::getPayload($credentials, $secret)['uid']);
            }
        } catch (InvalidArgumentException $e) {
            return $this->responseFactory->createResponse()
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401, 'Unauthorized');
        }

        return $handler->handle($request);
    }
}
