<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Messenger\Exception\MessengerException;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
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
     * @param MessengerRepository $messengerRepository
     * @param UserRepository      $userRepository
     */
    public function __construct(
        MessengerRepository $messengerRepository,
        UserRepository $userRepository
    ) {
        $this->messengerRepository = $messengerRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws MessengerException
     * @throws UserException
     */
    public function friends(Request $request, Response $response, $args): Response
    {
        $page = $args['page'];
        $resultPerPage = $args['rpp'];

        /** @var PaginatedArrayCollection */
        $friends = $this->messengerRepository->findPageBy($page, $resultPerPage, [
            'user' => $this->getUser($this->userRepository, $request)
        ], ['id' => 'DESC']);

        if ($friends->isEmpty()) {
            throw new MessengerException(__('You have no friends'), 404);
        }

        $list = [];
        foreach ($friends as $friend) {
            $list[] = $friend
                ->getFriend()
                ->getArrayCopy();
        }

        return $this->respond(
            $response,
            response()->setData([
                'pagination' => [
                    'totalPages' => $friends->getPages(),
                    'prevPage' => $friends->getPrevPage(),
                    'nextPage' => $friends->getNextPage()
                ],
                'friends' => $list
            ])
        );
    }
}
