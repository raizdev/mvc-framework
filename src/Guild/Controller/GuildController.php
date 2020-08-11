<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Entity\GuildMember;
use Ares\Guild\Exception\GuildException;
use Ares\Guild\Repository\GuildMemberRepository;
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
     * @var GuildMemberRepository
     */
    private GuildMemberRepository $guildMemberRepository;

    /**
     * RoomController constructor.
     *
     * @param GuildRepository       $guildRepository
     * @param GuildMemberRepository $guildMemberRepository
     */
    public function __construct(
        GuildRepository $guildRepository,
        GuildMemberRepository $guildMemberRepository
    ) {
        $this->guildRepository = $guildRepository;
        $this->guildMemberRepository = $guildMemberRepository;
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
        /** @var int $id */
        $id = $args['id'];

        /** @var Guild $guild */
        $guild = $this->guildRepository->get($id);

        /** @var GuildMember $memberCount */
        $memberCount = $this->guildMemberRepository->count([
            'guild' => $id
        ]);

        if (is_null($guild)) {
            throw new GuildException(__('No specific Guild found'));
        }

        /** @var Room $guildRoom */
        $guildRoom = [
            'member_count' => $memberCount,
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
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedArrayCollection */
        $guilds = $this->guildRepository->findPageBy(
            (int)$page,
            (int)$resultPerPage,
            [],
            ['id' => 'DESC']
        );

        if ($guilds->isEmpty()) {
            throw new GuildException(__('No Guilds were found'), 404);
        }

        /** @var PaginatedArrayCollection $list */
        $list = [];
        foreach ($guilds as $guild) {
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

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws GuildException
     */
    public function members(Request $request, Response $response, $args): Response
    {
        /** @var int $args */
        $id = $args['id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedArrayCollection */
        $members = $this->guildMemberRepository->findPageBy(
            (int)$page,
            (int)$resultPerPage,
            ['guild' => $id],
            ['id' => 'DESC']
        );

        if ($members->isEmpty()) {
            throw new GuildException(__('No Members were found for this Guild'), 404);
        }

        /** @var PaginatedArrayCollection $list */
        $list = [];
        foreach ($members as $member) {
            $list[] = $member->getArrayCopy();
        }

        return $this->respond(
            $response,
            response()->setData($list)
        );
    }
}
