<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller;

use League\Container\Container;
use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * BaseController constructor.
     *
     * @param   Container  $container
     */
    public function __construct(
        Container $container
    ) {
        $this->container = $container;
    }

    /**
     * @param   Response  $response
     * @param   mixed     $data
     * @param   int       $status
     *
     * @return Response
     */
    protected function jsonResponse(Response $response, $data = null, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        $response = $response->withStatus($status);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
