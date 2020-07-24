<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Controller;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class BaseController
 *
 * @package Ares\Framework\Controller
 */
abstract class BaseController
{
    /**
     * If the user is in the JWT Token data its id is returned
     *
     * @param Request $request
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
     * Determines the RealIP of the User and returns it
     *
     * @return string|null Returns the current User IP when given
     */
    protected function determineIp(): ?string
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
     * Creates json response.
     *
     * @param Response $response The current Response
     * @param mixed $customResponse The Data given into the Function
     * @return Response Returns a Response with the given Data
     */
    protected function jsonResponse(Response $response, CustomResponseInterface $customResponse): Response
    {
        $response->getBody()->write($customResponse->respond());

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
