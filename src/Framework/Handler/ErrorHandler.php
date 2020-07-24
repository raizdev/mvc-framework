<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Handler;

use Ares\Framework\Model\CustomResponse as CustomResponse;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

/**
 * Class ErrorHandler
 */
class ErrorHandler implements ErrorHandlerInterface
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
     * Catches exception and returns it in json format.
     *
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @param bool $displayErrorDetails
     * @param bool $logErrors
     * @param bool $logErrorDetails
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails): ResponseInterface
    {
        $customResponse = response()
            ->setStatus('error')
            ->setCode($exception->getCode())
            ->setMessage(get_class($exception))
            ->addError([
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

        $response = $this->responseFactory->createResponse();

        $response->getBody()->write($customResponse->getJson());

        try {
            $response = $response->withStatus($exception->getCode());
        } catch (\Exception $exception) {
            $response = $response->withStatus(500);
        }

        return $this->getResponseWithCorsHeader($request, $response);
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function getResponseWithCorsHeader(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $headers =  [
            "Content-Type" => "application/problem+json",
            "origin" => [$_ENV['WEB_FRONTEND_LINK']],
            "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
            "headers.allow" => ["Content-Type", "Authorization", "If-Match", "If-Unmodified-Since", "Origin"],
            "headers.expose" => ["Content-Type", "Etag", "Origin"],
            "credentials" => 'true',
            "cache" => $_ENV['TOKEN_DURATION']
        ];

        foreach ($headers as $key => $header) {
            $response = $response->withHeader($key, $header);
        }

        return $response;
    }
}