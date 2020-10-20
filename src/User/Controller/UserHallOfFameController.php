<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\CacheException;
use Ares\User\Repository\UserRepository;
use Ares\User\Repository\UserSettingRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @TODO    needs refactoring
 *
 * Class UserHallOfFameController
 *
 * @package Ares\User\Controller
 */
class UserHallOfFameController extends BaseController
{
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
     * @param UserRepository        $userRepository
     * @param UserSettingRepository $userSettingRepository
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
     * @throws CacheException
     */
    public function topCredits(Request $request, Response $response): Response
    {
        $searchCriteria = $this->userRepository
            ->getDataObjectManager()
            ->orderBy('credits', 'DESC')
            ->limit(3);

        $users = $this->userRepository->getList($searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CacheException
     */
    public function topDiamonds(Request $request, Response $response): Response
    {
        $searchCriteria = $this->userRepository
            ->getDataObjectManager()
            ->orderBy('points', 'DESC')
            ->limit(3);

        $users = $this->userRepository->getList($searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CacheException
     */
    public function topPixels(Request $request, Response $response): Response
    {
        $searchCriteria = $this->userRepository
            ->getDataObjectManager()
            ->orderBy('pixels', 'DESC')
            ->limit(3);

        $users = $this->userRepository->getList($searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CacheException
     */
    public function topAchievement(Request $request, Response $response): Response
    {
        $searchCriteria = $this->userSettingRepository
            ->getDataObjectManager()
            ->orderBy('achievement_score', 'DESC')
            ->limit(3);

        $users = $this->userSettingRepository->getList($searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CacheException
     */
    public function topOnlineTime(Request $request, Response $response): Response
    {
        $searchCriteria = $this->userSettingRepository
            ->getDataObjectManager()
            ->orderBy('online_time', 'DESC')
            ->limit(3);

        $users = $this->userSettingRepository->getList($searchCriteria);

        return $this->respond(
            $response,
            response()->setData($users)
        );
    }
}
