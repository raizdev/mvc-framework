<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class BaseController
 *
 * @package App\Controller
 */
abstract class BaseController
{
    /**
     * If the user is in the JWT Token data its id is returned
     *
     * @param   Request  $request
     *
     * @return int|null
     */
    protected function getAuthUser(Request $request): ?int
    {
        $user = $request->getAttribute('uid');
        if (isset($user)) {
            return json_decode(json_encode($user), true);
        }

        return null;
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
