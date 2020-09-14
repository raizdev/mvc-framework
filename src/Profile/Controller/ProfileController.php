<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Profile\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Guild\Repository\GuildRepository;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\Photo\Repository\PhotoRepository;
use Ares\Profile\Exception\ProfileException;
use Ares\Room\Repository\RoomRepository;
use Ares\User\Entity\User;
use Ares\User\Entity\UserBadge;
use Ares\User\Repository\UserBadgeRepository;
use Ares\User\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ProfileController
 *
 * @package Ares\Profile\Controller
 */
class ProfileController extends BaseController
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var RoomRepository
     */
    private RoomRepository $roomRepository;

    /**
     * @var GuildRepository
     */
    private GuildRepository $guildRepository;

    /**
     * @var MessengerRepository
     */
    private MessengerRepository $messengerRepository;

    /**
     * @var PhotoRepository
     */
    private PhotoRepository $photoRepository;

    /**
     * @var UserBadgeRepository
     */
    private UserBadgeRepository $userBadgeRepository;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * ProfileController constructor.
     *
     * @param   UserRepository          $userRepository
     * @param   RoomRepository          $roomRepository
     * @param   GuildRepository         $guildRepository
     * @param   MessengerRepository     $messengerRepository
     * @param   PhotoRepository         $photoRepository
     * @param   UserBadgeRepository     $userBadgeRepository
     * @param   DoctrineSearchCriteria  $searchCriteria
     */
    public function __construct(
        UserRepository $userRepository,
        RoomRepository $roomRepository,
        GuildRepository $guildRepository,
        MessengerRepository $messengerRepository,
        PhotoRepository $photoRepository,
        UserBadgeRepository $userBadgeRepository,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->userRepository      = $userRepository;
        $this->roomRepository      = $roomRepository;
        $this->guildRepository     = $guildRepository;
        $this->messengerRepository = $messengerRepository;
        $this->photoRepository     = $photoRepository;
        $this->userBadgeRepository = $userBadgeRepository;
        $this->searchCriteria      = $searchCriteria;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws ProfileException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function slotBadges(Request $request, Response $response, $args): Response
    {
        /** @var int $profile_id */
        $profile_id = $args['profile_id'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profile_id);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        /** @var UserBadge $badges */
        $badges = $this->userBadgeRepository->getSlotBadges($profile);

        if (!$badges) {
            throw new ProfileException(__('Profile has no slotted Badges'), 404);
        }

        return $this->respond(
            $response,
            response()
                ->setData($badges)
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ProfileException
     */
    public function badgeList(Request $request, Response $response, $args): Response
    {
        /** @var int $profile_id */
        $profile_id = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profile_id);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $this->searchCriteria->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addOrder('id', 'DESC');

        /** @var ArrayCollection $pinnedArticles */
        $badges = $this->userBadgeRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $badges->toArray()
                )
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ProfileException
     */
    public function friendList(Request $request, Response $response, $args): Response
    {
        /** @var int $profile_id */
        $profile_id = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profile_id);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $this->searchCriteria
            ->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addFilter('user', (int) $profile_id)
            ->addOrder('id', 'DESC');

        /** @var ArrayCollection $pinnedArticles */
        $friends = $this->messengerRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $friends->toArray()
                )
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ProfileException
     */
    public function roomList(Request $request, Response $response, $args): Response
    {
        /** @var int $profile_id */
        $profile_id = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profile_id);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $this->searchCriteria
            ->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addFilter('owner', (int) $profile_id)
            ->addOrder('id', 'DESC');

        $rooms = $this->roomRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $rooms->toArray()
                )
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ProfileException
     */
    public function guildList(Request $request, Response $response, $args): Response
    {
        /** @var int $profile_id */
        $profile_id = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profile_id);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $this->searchCriteria
            ->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addFilter('creator', (int) $profile_id)
            ->addOrder('id', 'DESC');

        $guilds = $this->guildRepository->profileGuilds($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $guilds->toArray()
                )
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ProfileException
     */
    public function photoList(Request $request, Response $response, $args): Response
    {
        /** @var int $profile_id */
        $profile_id = $args['profile_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profile_id);

        if (!$profile) {
            throw new ProfileException(__('No associated Profile was found'), 404);
        }

        $this->searchCriteria
            ->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addFilter('creator', (int) $profile_id)
            ->addOrder('id', 'DESC');

        $photos = $this->photoRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $photos->toArray()
                )
        );
    }
}
