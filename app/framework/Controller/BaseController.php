<?php declare(strict_types=1);
namespace Raizdev\Framework\Controller;

use Raizdev\Framework\Interfaces\CustomResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class BaseController
 *
 */
abstract class BaseController
{
    /**
     * Creates json response.
     *
     * @param Response $response The current Response
     * @param mixed $customResponse The Data given into the Function
     * @return Response Returns a Response with the given Data
     */
    protected function respond(Response $response, CustomResponseInterface $customResponse): Response
    {
        $response->getBody()->write($customResponse->getJson());

        return $response
            ->withStatus(
                $customResponse
                    ->getCode()
            )
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
}
