<?php
namespace StarreDEV\Framework\Controller;

use StarreDEV\Framework\Handler\TwigViewResponseHandler;
use StarreDEV\Framework\Interfaces\CustomResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use StarreDEV\Framework\Model\Validation;

abstract class BaseController  {

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
?>