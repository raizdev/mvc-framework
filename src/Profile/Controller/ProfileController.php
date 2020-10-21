<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Profile\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\CacheException;
use Ares\Guild\Repository\GuildRepository;
use Ares\Messenger\Repository\MessengerRepository;
use Ares\Photo\Repository\PhotoRepository;
use Ares\Profile\Exception\ProfileException;
use Ares\Room\Repository\RoomRepository;
use Ares\User\Entity\User;
use Ares\User\Entity\UserBadge;
use Ares\User\Repository\UserBadgeRepository;
use Ares\User\Repository\UserRepository;
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
     * ProfileController constructor.
     *
     * @param   UserRepository          $userRepository
     * @param   RoomRepository          $roomRepository
     * @param   GuildRepository         $guildRepository
     * @param   MessengerRepository     $messengerRepository
     * @param   PhotoRepository         $photoRepository
     * @param   UserBadgeRepository     $userBadgeRepository
     */
    public function __construct(
        UserRepository $userRepository,
        RoomRepository $roomRepository,
        GuildRepository $guildRepository,
        MessengerRepository $messengerRepository,
        PhotoRepository $photoRepository,
        UserBadgeRepository $userBadgeRepository
    ) {
        $this->userRepository      = $userRepository;
        $this->roomRepository      = $roomRepository;
        $this->guildRepository     = $guildRepository;
        $this->messengerRepository = $messengerRepository;
        $this->photoRepository     = $photoRepository;
        $this->userBadgeRepository = $userBadgeRepository;
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ProfileException
     * @throws CacheException
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

        $searchCriteria = $this->userBadgeRepository
            ->getDataObjectManager()
            ->where([
                'user_id' => $profile->getId()
            ])
            ->where('slot_id', '>', 1)
            ->orderBy('slot_id', 'ASC');

        /** @var UserBadge $badges */
        $badges = $this->userBadgeRepository->getList($searchCriteria);

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
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
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

        $searchCriteria = $this->userBadgeRepository
            ->getDataObjectManager()
            ->orderBy('id', 'DESC');

        $badges = $this->userBadgeRepository
            ->getPaginatedList($searchCriteria, (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($badges)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
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

        $searchCriteria = $this->messengerRepository
            ->getDataObjectManager()
            ->where('user_id', (int) $profile_id)
            ->orderBy('id', 'DESC');

        $friends = $this->messengerRepository
            ->getPaginatedList($searchCriteria, (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($friends)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
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

        $searchCriteria = $this->roomRepository
            ->getDataObjectManager()
            ->where('owner_id')
            ->orderBy('id', 'DESC');

        $rooms = $this->roomRepository
            ->getPaginatedList($searchCriteria, (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($rooms)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
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

        $searchCriteria = $this->guildRepository
            ->getDataObjectManager()
            ->where('user_id', (int) $profile_id)
            ->orderBy('id', 'DESC');

        /**
         * @TODO Refactor this and get all Guilds associated to the Profile
         */
        $guilds = $this->guildRepository->profileGuilds($searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData($guilds)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
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

        $searchCriteria = $this->photoRepository
            ->getDataObjectManager()
            ->where('user_id', (int) $profile_id)
            ->orderBy('id', 'DESC');

        $photos = $this->photoRepository
            ->getPaginatedList($searchCriteria, (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($photos)
        );
    }
}
