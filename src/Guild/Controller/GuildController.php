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
use Ares\Guild\Repository\GuildMemberRepository;
use Ares\Guild\Repository\GuildRepository;
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
     * @param   GuildRepository         $guildRepository
     * @param   GuildMemberRepository   $guildMemberRepository
     */
    public function __construct(
        GuildRepository $guildRepository,
        GuildMemberRepository $guildMemberRepository
    ) {
        $this->guildRepository       = $guildRepository;
        $this->guildMemberRepository = $guildMemberRepository;
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws GuildException
     */
    public function guild(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Guild $guild */
        $guild = $this->guildRepository->get((int) $id);

        if (!$guild) {
            throw new GuildException(__('No specific Guild found'));
        }

        $searchCriteria = $this->guildMemberRepository
            ->getDataObjectManager()
            ->where('guild_id', $guild->getId());

        $memberCount = $this->guildMemberRepository
            ->getList($searchCriteria)
            ->count();

        return $this->respond(
            $response,
            response()
                ->setData([
                    'guild'        => $guild,
                    'member_count' => $memberCount
                ])
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $searchCriteria = $this->guildRepository
            ->getDataObjectManager()
            ->orderBy('id', 'DESC');

        $guilds = $this->guildRepository
            ->getPaginatedList($searchCriteria, (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($guilds)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     */
    public function members(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $guildId = $args['guild_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $searchCriteria = $this->guildMemberRepository
            ->getDataObjectManager()
            ->where('guild_id', (int) $guildId)
            ->orderBy('level_id', 'ASC');

        $members = $this->guildMemberRepository
            ->getPaginatedList($searchCriteria, (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($members)
        );
    }

    /**
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws GuildException
     */
    public function mostMembers(Request $request, Response $response): Response
    {
        $results = $this->guildMemberRepository->getMemberCountByGuild();

        if (!$results) {
            throw new GuildException(__('No Guild were found'), 404);
        }

        $getMaxMemberGuild = array_shift($results);

        /** @var Guild $guild */
        $guild = $this->guildRepository->get($getMaxMemberGuild['id']);

        return $this->respond(
            $response,
            response()
                ->setData([
                    'guild'        => $guild,
                    'member_count' => $getMaxMemberGuild['member']
                ])
        );
    }
}
