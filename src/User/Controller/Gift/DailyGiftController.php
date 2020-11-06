<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller\Gift;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\User\Entity\User;
use Ares\User\Exception\Gift\DailyGiftException;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Ares\User\Service\Gift\PickGiftService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class DailyGiftController
 *
 * @package Ares\User\Controller\Gift
 */
class DailyGiftController extends BaseController
{
    /**
     * @var PickGiftService
     */
    private PickGiftService $pickGiftService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * DailyGiftController constructor.
     *
     * @param PickGiftService $pickGiftService
     * @param UserRepository $userRepository
     */
    public function __construct(
        PickGiftService $pickGiftService,
        UserRepository $userRepository
    ) {
        $this->pickGiftService = $pickGiftService;
        $this->userRepository = $userRepository;
    }

    /**
     * Pick daily gift route.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DailyGiftException
     * @throws DataObjectManagerException
     * @throws UserException
     */
    public function pick(Request $request, Response $response): ResponseInterface
    {
        /** @var User $user */
        $user = user($request);

        $customResponse = $this->pickGiftService->execute($user);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
