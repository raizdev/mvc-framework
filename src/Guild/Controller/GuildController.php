<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Exception\GuildException;
use Ares\Guild\Repository\GuildRepository;
use Ares\Room\Entity\Room;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class GuildController
 *
 * @package Ares\Guild\Controller
 */
class GuildController extends BaseController
{
    /**
     * @var GuildRepository
     */
    private GuildRepository $guildRepository;

    /**
     * RoomController constructor.
     *
     * @param GuildRepository $guildRepository
     */
    public function __construct(
        GuildRepository $guildRepository
    ) {
        $this->guildRepository = $guildRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param          $args
     *
     * @return Response
     * @throws GuildException
     */
    public function guild(Request $request, Response $response, $args): Response
    {
        /** @var Guild $guild */
        $guild = $this->guildRepository->get((int)$args['id']);

        if (is_null($guild)) {
            throw new GuildException(__('No specific Guild found'));
        }

        /** @var Room $guildRoom */
        $guildRoom = [
            'room' => [
                'name' => $guild->getRoom()->getName(),
                'description' => $guild->getRoom()->getDescription(),
                'state' => $guild->getRoom()->getState(),
                'users' => $guild->getRoom()->getUsers(),
                'users_max' => $guild->getRoom()->getUsersMax(),
                'score' => $guild->getRoom()->getScore(),
                'creator' => $guild->getRoom()->getOwner()->getArrayCopy()
            ]
        ];

        return $this->respond(
            $response,
            response()->setData(array_merge($guild->getArrayCopy(), $guildRoom))
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws GuildException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        $page = $args['page'];
        $resultPerPage = $args['rpp'];

        /** @var PaginatedArrayCollection */
        $guilds = $this->guildRepository->findPageBy((int)$page, (int)$resultPerPage, [], ['id' => 'DESC']);

        if ($guilds->isEmpty()) {
            throw new GuildException(__('No Guilds were found'), 404);
        }

        $list = [];
        foreach ($guilds as $guild) {
            /** @var Room $guildRoom */
            $guildRoom = [
                'room' => [
                    'name' => $guild->getRoom()->getName(),
                    'description' => $guild->getRoom()->getDescription(),
                    'state' => $guild->getRoom()->getState(),
                    'users' => $guild->getRoom()->getUsers(),
                    'users_max' => $guild->getRoom()->getUsersMax(),
                    'score' => $guild->getRoom()->getScore(),
                    'creator' => $guild->getRoom()->getOwner()->getArrayCopy()
                ]
            ];
            $list[] = array_merge($guild->getArrayCopy(), $guildRoom);
        }

        return $this->respond(
            $response,
            response()->setData($list)
        );
    }
}
