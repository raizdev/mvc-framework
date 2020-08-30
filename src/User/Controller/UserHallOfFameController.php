<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Ares\User\Repository\UserSettingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @TODO needs refactoring
 *
 * Class UserHallOfFameController
 *
 * @package Ares\User\Controller
 */
class UserHallOfFameController extends BaseController
{
    /** @var int */
    private const TOP_USER_QUANTITY = 3;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserSettingRepository
     */
    private UserSettingRepository $userSettingRepository;

    /**
     * UserHallOfFameController constructor.
     *
     * @param   UserRepository         $userRepository
     * @param   UserSettingRepository  $userSettingRepository
     */
    public function __construct(
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserException
     */
    public function topCredits(Request $request, Response $response): Response
    {
        /** @var ArrayCollection $users */
        $users = $this->userRepository->findBy([], [
            'credits' => 'DESC'
        ], self::TOP_USER_QUANTITY);

        if ($users->isEmpty()) {
            throw new UserException(__('There were no Top Users found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($users->toArray())
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserException
     */
    public function topDiamonds(Request $request, Response $response): Response
    {
        /** @var ArrayCollection $users */
        $users = $this->userRepository->findBy([], [
            'points' => 'DESC'
        ], self::TOP_USER_QUANTITY);

        if ($users->isEmpty()) {
            throw new UserException(__('There were no Top Users found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($users->toArray())
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserException
     */
    public function topPixels(Request $request, Response $response): Response
    {
        /** @var ArrayCollection $users */
        $users = $this->userRepository->findBy([], [
            'pixels' => 'DESC'
        ], self::TOP_USER_QUANTITY);

        if ($users->isEmpty()) {
            throw new UserException(__('There were no Top Users found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($users->toArray())
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserException
     */
    public function topAchievement(Request $request, Response $response): Response
    {
        /** @var ArrayCollection $users */
        $users = $this->userSettingRepository->findBy([], [
           'achievement_score' => 'DESC'
        ], self::TOP_USER_QUANTITY);

        if ($users->isEmpty()) {
            throw new UserException(__('There were no Top Users found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($users->toArray())
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserException
     */
    public function topOnlineTime(Request $request, Response $response): Response
    {
        /** @var ArrayCollection $users */
        $users = $this->userSettingRepository->findBy([], [
            'online_time' => 'DESC'
        ], self::TOP_USER_QUANTITY);

        if ($users->isEmpty()) {
            throw new UserException(__('There were no Top Users found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($users->toArray())
        );
    }
}
