<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
     * @param   UserRepository  $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     */
    public function all(Request $request, Response $response): Response
    {
        $users      = $this->userRepository->all();
        $usersArray = [];

        foreach ($users as $user) {
            $usersArray[] = $user->getArrayCopy();
        }

        return $this->jsonResponse(
            $response,
            $usersArray,
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
        $authUser = $this->authUser($request);
        $user     = $this->userRepository->find($authUser);

        return $this->jsonResponse(
            $response,
            $user->getArrayCopy(),
            200
        );
    }
}
