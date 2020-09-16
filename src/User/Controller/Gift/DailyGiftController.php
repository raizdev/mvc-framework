<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller\Gift;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
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
     * @var ValidationService
     */
    private ValidationService $validationService;

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
     * @param ValidationService $validationService
     * @param PickGiftService $pickGiftService
     * @param UserRepository $userRepository
     */
    public function __construct(
        ValidationService $validationService,
        PickGiftService $pickGiftService,
        UserRepository $userRepository
    ) {
        $this->validationService = $validationService;
        $this->pickGiftService = $pickGiftService;
        $this->userRepository = $userRepository;
    }

    /**
     * Pick daily gift route.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ValidationException
     */
    public function pick(Request $request, Response $response): ResponseInterface
    {
        /** @var array $body */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'user_id' => 'required'
        ]);

        $user = $this->getUser($this->userRepository, $request, false);

        $customReponse = $this->pickGiftService->execute($user);

        return $this->respond(
            $response,
            $customReponse
        );
    }
}