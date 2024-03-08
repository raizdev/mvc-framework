<?php declare(strict_types=1);

namespace StarreDEV\Framework\Middleware;

/**
 * Import classes
 */
use Middlewares\Utils\HttpErrorException as InvalidPayloadException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sunrise\Http\Router\Exception\BadRequestException;
use Sunrise\Http\Router\Exception\MethodNotAllowedException;
use Sunrise\Http\Router\Exception\PageNotFoundException;
use Sunrise\Http\Router\Exception\UnsupportedMediaTypeException;
use Sunrise\Http\Message\ResponseFactory;
use Psr\Http\Message\ResponseFactoryInterface;

use StarreDEV\Framework\Interfaces\CustomResponseCodeInterface;
use StarreDEV\Framework\Interfaces\HttpResponseCodeInterface;
use StarreDEV\Framework\Interfaces\CustomResponseInterface;

use Throwable;

/**
 * ErrorHandlingMiddleware
 */
final class ErrorHandlingMiddleware implements MiddlewareInterface
{

    /**
     * {@inheritDoc}
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ) : ResponseInterface {

        try {
            return $handler->handle($request);
        } catch (InvalidPayloadException $e) {
            return $this->handleInvalidPayload($request, $e);
        } catch (BadRequestException $e) {
            return $this->handleBadRequest($request, $e);
        } catch (PageNotFoundException $e) {
            return $this->handlePageNotFound($request, $e);
        } catch (MethodNotAllowedException $e) {
            return $this->handleMethodNotAllowed($request, $e);
        } catch (UnsupportedMediaTypeException $e) {
            return $this->handleUnsupportedMediaType($request, $e);
        } catch (Throwable $e) {
            return $this->handleUnexpectedError($request, $e);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param InvalidPayloadException $e
     *
     * @return ResponseInterface
     */
    private function handleInvalidPayload(
        ServerRequestInterface $request,
        InvalidPayloadException $e
    ) : ResponseInterface {
        return $this->error('Invalid payload', 'requestBody', null, 400);
    }

    /**
     * @param ServerRequestInterface $request
     * @param BadRequestException $e
     *
     * @return ResponseInterface
     */
    private function handleBadRequest(
        ServerRequestInterface $request,
        BadRequestException $e
    ) : ResponseInterface {
        return $this->violations($e->getViolations(), 400);
    }

    /**
     * @param ServerRequestInterface $request
     * @param PageNotFoundException $e
     *
     * @return ResponseInterface
     */
    private function handlePageNotFound(
        ServerRequestInterface $request,
        PageNotFoundException $e
    ) : ResponseInterface {
        return $this->createResponse(404);
    }

    /**
     * @param ServerRequestInterface $request
     * @param MethodNotAllowedException $e
     *
     * @return ResponseInterface
     */
    private function handleMethodNotAllowed(
        ServerRequestInterface $request,
        MethodNotAllowedException $e
    ) : ResponseInterface {
        return $this->createResponse(405)
            ->withHeader('Allow', $e->getJoinedAllowedMethods());
    }

    /**
     * @param ServerRequestInterface $request
     * @param UnsupportedMediaTypeException $e
     *
     * @return ResponseInterface
     */
    private function handleUnsupportedMediaType(
        ServerRequestInterface $request,
        UnsupportedMediaTypeException $e
    ) : ResponseInterface {
        return $this->createResponse(415)
            ->withHeader('Accept', $e->getJoinedSupportedTypes());
    }

    /**
     * @param ServerRequestInterface $request
     * @param Throwable $e
     *
     * @return ResponseInterface
     */
    private function handleUnexpectedError(
        ServerRequestInterface $request,
        Throwable $exception
    ) : ResponseInterface {

            if (!$exception instanceof BaseException) {
                $statusCode = $exception->getCode() ?: HttpResponseCodeInterface::HTTP_RESPONSE_INTERNAL_SERVER_ERROR;
            } else {
                $statusCode = $exception->getCustomCode() ?: CustomResponseCodeInterface::RESPONSE_UNKNOWN_ERROR;
            }

            $customResponse = response()
            ->setStatus('error')
            ->setCode($statusCode)
            ->setException(get_class($exception));

            $custom = $this->addErrors($customResponse, $exception);
            $response = new ResponseFactory();
            $response = $response->createResponse();
            $response->getBody()->write($customResponse->getJson());

            return $this->withCorsHeader($request, $response);
    }

        /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    private function withCorsHeader(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        return $response
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader("Content-Type", "application/json");
    }

     /**
     * @param CustomResponseInterface $customResponse
     * @param Throwable $exception
     * @return CustomResponseInterface
     */
    private function addErrors(CustomResponseInterface $customResponse, Throwable $exception): CustomResponseInterface
    {
        if (!$exception instanceof BaseException) {
            $this->addTrace($customResponse, $exception);
            return $customResponse->addError([
                'message' => $exception->getMessage()
            ]);
        }

      if (!$errors) {
            $this->addTrace($customResponse, $exception);
            return $customResponse->addError([
                'message' => $exception->getMessage()
            ]);
        }

        foreach ($errors as $error) {
            $customResponse->addError($error);
        }

        return $customResponse;
    }

    /**
     * Adds Trace of Error if API is in development mode
     *
     * @param CustomResponseInterface $customResponse
     * @param Throwable               $exception
     */
    private function addTrace(CustomResponseInterface $customResponse, Throwable $exception): void
    {
            $customResponse->addError([
                'trace' => $exception->getTraceAsString()
            ]);
    }
}