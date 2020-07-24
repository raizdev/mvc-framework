<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\User\Exception\UserNotFoundException;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserController
 *
 * @package Ares\Framework\Controller\User
 */
class UserController extends BaseController
{
    /**
     * @var UserRepository Gets the current UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Retrieves all User from the Database and copies an Array of Values from every User and returns it
     *
     * @param Request  $request  The current incoming Request
     * @param Response $response The current Response
     * @return Response Returns a Response with the given Data
     */
    public function all(Request $request, Response $response): Response
    {
        $users = $this->userRepository->all();
        $usersArray = [];

        foreach ($users as $user) {
            $usersArray[] = $user->getArrayCopy();
        }

        $customResponse = response()->setData($usersArray);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Retrieves the logged in User via JWT - Token
     *
     * @param Request $request The current incoming Request
     * @param Response $response The current Response
     * @return Response Returns a Response with the given Data
     * @throws UserNotFoundException
     */
    public function user(Request $request, Response $response): Response
    {
        $authUser = $this->authUser($request);
        $user = $this->userRepository->find($authUser);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $customResponse = response()->setData($user->getArrayCopy());

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
