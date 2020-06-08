<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;

abstract class BaseController
{
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
