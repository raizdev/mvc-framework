<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\ORMException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserController
 *
 * @package Ares\User\Controller
 */
class UserController extends BaseController
{
    /**
     * 2 equals the second field in the enum-field
     */
    private const USER_EQUALS_ONLINE = 2;

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
     * Retrieves the logged in User via JWT - Token
     *
     * @param Request $request The current incoming Request
     * @param Response $response The current Response
     * @return Response Returns a Response with the given Data
     * @throws UserException
     */
    public function user(Request $request, Response $response): Response
    {
        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request);

        return $this->respond(
            $response,
            response()->setData($user->getArrayCopy())
        );
    }

    /**
     * Gets all current Online User and counts them
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function onlineUser(Request $request, Response $response): Response
    {
        $onlineUser = $this->userRepository->count([
            'online' => self::USER_EQUALS_ONLINE
        ]);

        return $this->respond(
            $response,
            response()->setData([
                'count' => $onlineUser
            ])
        );
    }

    /**
     * Saves the given Language to the User
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ORMException
     * @throws UserException
     */
    public function updateLocale(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $user = $this->getUser($this->userRepository, $request);
        $user->setLocale($body['locale']);

        $this->userRepository->save($user);

        return $this->respond(
            $response,
            response()->setData($user->getArrayCopy())
        );
    }
}
