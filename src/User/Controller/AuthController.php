<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\User\Entity\User;
use Ares\User\Exception\LoginException;
use Ares\User\Exception\RegisterException;
use Ares\User\Repository\UserRepository;
use Ares\Framework\Service\TokenService;
use Ares\Framework\Service\ValidationService;
use Exception;
use PHLAK\Config\Config;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class AuthController
 *
 * @package Ares\Framework\Controller\Auth
 */
class AuthController extends BaseController
{
    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * AuthController constructor.
     *
     * @param UserRepository    $userRepository
     * @param ValidationService $validationService
     * @param Config            $config
     * @param TokenService      $tokenService
     */
    public function __construct(
        UserRepository $userRepository,
        ValidationService $validationService,
        Config $config,
        TokenService $tokenService
    ) {
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->config = $config;
        $this->tokenService = $tokenService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     * @throws Exception
     */
    public function login(Request $request, Response $response): Response
    {
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'username' => 'required|min:3',
            'password' => 'required'
        ]);

        /** @var User $user */
        $user = $this->userRepository->findByUsername($parsedData['username']);

        if (empty($user) || !password_verify($parsedData['password'], $user->getPassword())) {
            throw new LoginException(__('Woops something went wrong.'), 403);
        }

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        $customResponse = response()->setData([
            'token' => $token
        ]);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     * @throws Exception
     */
    public function register(Request $request, Response $response): Response
    {
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'username' => 'required|min:3',
            'mail'     => 'required|email|min:9',
            'password' => 'required'
        ]);

        /** @var User $user */
        $user = $this->userRepository->findByUsername($parsedData['username']);

        if (!is_null($user)) {
            throw new RegisterException(__('User already exists.'), 422);
        }

        $data = [
            'username' => $parsedData['username'],
            'password' => $parsedData['password'],
            'mail' => $parsedData['mail'],
            'look' => $this->config->get('hotel_settings.start_look'),
            'credits' => $this->config->get('hotel_settings.start_credits'),
            'points' => $this->config->get('hotel_settings.start_points'),
            'pixels' => $this->config->get('hotel_settings.start_pixels'),
            'motto' => $this->config->get('hotel_settings.start_motto'),
            'ip_register' => $this->determineIp(),
            'ip_current' => $this->determineIp(),
            'account_created' => time(),
            'auth_ticket' => 'xddd'
        ];

        $user = new User();
        $user = $this->userRepository->create($user ,$data);

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        $customResponse = response()->setData([
            'token' => $token
        ]);

        return $this->respond($response, $customResponse);
    }

    /**
     * @TODO TBD - Need to write something that unvalidates the token...
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     */
    public function logout(Request $request, Response $response): Response
    {
    }
}
