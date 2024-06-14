<?php declare(strict_types=1);
namespace Raizdev\Framework\Response\Handler;

use Raizdev\Framework\Response\ResponseType;
use Psr\Http\Message\ResponseInterface;

/**
 * Response type handler.
 */
interface ResponseTypeHandler
{
    /**
     * Handle response.
     *
     * @param ResponseType $responseType
     *
     * @return ResponseInterface
     */
    public function handle(ResponseType $responseType): ResponseInterface;
}