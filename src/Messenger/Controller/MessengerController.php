<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class MessengerController
 *
 * @package Ares\Messenger\Controller
 */
class MessengerController extends BaseController
{
    /**
     * @var MessengerRepository
     */
    private MessengerRepository $messengerRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * MessengerController constructor.
     *
     * @param MessengerRepository    $messengerRepository
     * @param UserRepository         $userRepository
     */
    public function __construct(
        MessengerRepository $messengerRepository,
        UserRepository $userRepository
    ) {
        $this->messengerRepository = $messengerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     * @throws UserException
     * @throws DataObjectManagerException
     */
    public function friends(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request);

        $friends = $this->messengerRepository
            ->getPaginatedMessengerFriends($user->getId(), (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($friends)
        );
    }
}
