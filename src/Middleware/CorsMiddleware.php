<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

/**
 * Class CorsMiddleware
 *
 * @package App\Middleware
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * Invoke Cors middleware
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
        $routeContext   = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods        = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);

        $response = $response->withHeader('Access-Control-Allow-Origin', getenv('WEB_FRONTEND_LINK'));
        $response = $response->withHeader('Access-Control-Allow-Methods', implode(', ', $methods));
        $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders ?: '*');

        // Allow Ajax CORS requests with Authorization header
        $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');


        return $response;
    }
}