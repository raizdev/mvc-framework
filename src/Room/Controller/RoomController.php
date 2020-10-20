<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\CacheException;
use Ares\Room\Entity\Room;
use Ares\Room\Exception\RoomException;
use Ares\Room\Repository\RoomRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RoomController
 *
 * @package Ares\Room\Controller
 */
class RoomController extends BaseController
{
    /**
     * @var RoomRepository
     */
    private RoomRepository $roomRepository;

    /**
     * RoomController constructor.
     *
     * @param RoomRepository $roomRepository
     */
    public function __construct(
        RoomRepository $roomRepository
    ) {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param          $args
     *
     * @return Response
     * @throws RoomException
     * @throws CacheException
     */
    public function room(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Room $room */
        $room = $this->roomRepository->get((int)$id);

        if (!$room) {
            throw new RoomException(__('No specific Room found'), 404);
        }

        return $this->respond(
            $response,
            response()
                ->setData($room)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws CacheException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $searchCriteria = $this->roomRepository
            ->getDataObjectManager()
            ->orderBy('id', 'DESC');

        $rooms = $this->roomRepository->getPaginatedList($searchCriteria, (int)$page, (int)$resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($rooms)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CacheException
     * @throws RoomException
     */
    public function mostVisited(Request $request, Response $response): Response
    {
        $searchCriteria = $this->roomRepository
            ->getDataObjectManager()
            ->orderBy('users', 'DESC');

        /** @var Room $room */
        $room = $this->roomRepository
            ->getList($searchCriteria)
            ->first();

        if (!$room) {
            throw new RoomException(__('No Room found'), 404);
        }

        return $this->respond(
            $response,
            response()
                ->setData($room)
        );
    }
}
