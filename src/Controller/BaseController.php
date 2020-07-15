<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
     * @param Request $request
     *
     * @return int|null
     */
    protected function authUser(Request $request): ?int
    {
        $user = $request->getAttribute('ares_uid');
        if (isset($user)) {
            return json_decode(json_encode($user), true);
        }

        return null;
    }

    /**
     * @param Request $request
     */
    protected function determineLang(Request $request)
    {

    }

    /**
     * Determines the RealIP of the User and returns it
     *
     * @return int|null Returns the current User IP when given
     */
    protected function determineIp(): ?int
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * @param Response $response The current Response
     * @param mixed    $data The Data given into the Function
     * @param int      $status The StatusCode given to the Response
     *
     * @return Response Returns a Response with the given Data
     */
    protected function jsonResponse(Response $response, $data = null, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        $response = $response->withStatus($status);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
