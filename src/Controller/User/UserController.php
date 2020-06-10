<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Repository\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserController
 *
 * @package App\Controller\User
 */
class UserController extends BaseController
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserController constructor.
     *
     * @param   UserRepository      $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository     = $userRepository;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     */
    public function all(Request $request, Response $response): Response
    {
        $user = $this->userRepository->all();

        return $this->jsonResponse(
            $response,
            $user,
            200
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     */
    public function user(Request $request, Response $response): Response
    {
        return $this->jsonResponse(
            $response,
            'works',
            200
        );
    }
}