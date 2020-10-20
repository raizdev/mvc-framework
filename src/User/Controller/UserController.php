<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\CacheException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
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
     * @var UserRepository Gets the current UserRepository
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
     * Retrieves the logged in User via JWT - Token
     *
     * @param Request  $request  The current incoming Request
     * @param Response $response The current Response
     *
     * @return Response Returns a Response with the given Data
     * @throws UserException
     * @throws CacheException
     */
    public function user(Request $request, Response $response): Response
    {
        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request);
        $user->getRoles();

        return $this->respond(
            $response,
            response()
                ->setData($user)
        );
    }

    /**
     * Gets all current Online User and counts them
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CacheException
     */
    public function onlineUser(Request $request, Response $response): Response
    {
        $searchCriteria = $this->userRepository
            ->getDataObjectManager()
            ->where('online', '1');

        /** @var User $onlineUser */
        $onlineUser = $this->userRepository
            ->getList($searchCriteria, false)
            ->count();

        return $this->respond(
            $response,
            response()
                ->setData([
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
     * @throws CacheException
     * @throws UserException
     * @throws DataObjectManagerException
     */
    public function updateLocale(Request $request, Response $response): Response
    {
        /** @var array $body */
        $body = $request->getParsedBody();

        /** @var User $user */
        $user = $user = $this->getUser($this->userRepository, $request);
        $user->setLocale($body['locale']);

        $this->userRepository->save($user);

        return $this->respond(
            $response,
            response()
                ->setData($user)
        );
    }
}
