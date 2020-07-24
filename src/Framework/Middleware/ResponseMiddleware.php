<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Middleware;

use Ares\Framework\Model\CustomResponse as CustomResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ResponseMiddleware
 */
class ResponseMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;
    /**
     * @var CustomResponse
     */
    private $customResponse;

    /**
     * ResponseMiddleware constructor.
     *
     * @param ResponseFactoryInterface $responseFactory
     * @param CustomResponse $customResponse
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        CustomResponse $customResponse
    ) {
        $this->responseFactory = $responseFactory;
        $this->customResponse = $customResponse;
    }

    /**
     * Process an incoming server request.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $this->customResponse->setData(['asdf' => 'movie']);

        $response = $this->responseFactory->createResponse();
        $response->getBody()->write($this->customResponse->getJson());

        return $response;
    }
}