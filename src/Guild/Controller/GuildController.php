<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Entity\GuildMember;
use Ares\Guild\Exception\GuildException;
use Ares\Guild\Repository\GuildMemberRepository;
use Ares\Guild\Repository\GuildRepository;
use Ares\Room\Entity\Room;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
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
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * RoomController constructor.
     *
     * @param GuildRepository        $guildRepository
     * @param GuildMemberRepository  $guildMemberRepository
     * @param DoctrineSearchCriteria $searchCriteria
     */
    public function __construct(
        GuildRepository $guildRepository,
        GuildMemberRepository $guildMemberRepository,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->guildRepository = $guildRepository;
        $this->guildMemberRepository = $guildMemberRepository;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param          $args
     *
     * @return Response
     * @throws GuildException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
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

        $guild->getRoom()->setGuild(null);
        $guild->getRoom()->setOwner(null);

        return $this->respond(
            $response,
            response()->setData([
                'guild' => $guild,
                'member_count' => $memberCount
            ])
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
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addOrder('id', 'DESC');

        $guilds = $this->guildRepository->paginate($this->searchCriteria);

        if ($guilds->isEmpty()) {
            throw new GuildException(__('No Guilds were found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($guilds->toArray())
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
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function members(Request $request, Response $response, $args): Response
    {
        /** @var int $args */
        $id = $args['id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addFilter('guild', $id)
            ->addOrder('id', 'DESC');

        $members = $this->guildMemberRepository->paginate($this->searchCriteria);

        if ($members->isEmpty()) {
            throw new GuildException(__('No Members were found for this Guild'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($members->toArray())
        );
    }

    /**
     * @TODO count most members and return the Group
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws GuildException
     */
    public function mostMembers(Request $request, Response $response): Response
    {
        /** @var Guild $guild */
        $guild = $this->guildMemberRepository->countBy();

        if (is_null($guild)) {
            throw new GuildException(__('No Group found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($guild)
        );
    }
}
