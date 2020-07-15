<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Repository\User\UserRepository;
use App\Service\TokenService;
use App\Service\ValidationService;
use PHLAK\Config\Config;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class AuthController
 *
 * @package App\Controller\Auth
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
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * AuthController constructor.
     *
     * @param UserRepository    $userRepository
     * @param ValidationService $validationService
     * @param TokenService      $tokenService
     * @param Config            $config
     */
    public function __construct(
        UserRepository $userRepository,
        ValidationService $validationService,
        TokenService $tokenService,
        Config $config
    ) {
        $this->userRepository    = $userRepository;
        $this->validationService = $validationService;
        $this->tokenService      = $tokenService;
        $this->config            = $config;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response Returns a Response with the given Data
     */
    public function login(Request $request, Response $response): Response
    {
        $parsedData = $request->getParsedBody();

        $validation = $this->validationService->execute($parsedData, [
            'username' => v::noWhitespace()->notEmpty()->stringType(),
            'password' => v::notEmpty()->stringType()
        ]);

        if ($validation->failed()) {
            return $this->jsonResponse($response, [
                'message' => 'Please check your provided data'
            ], 422);
        }

        $user = $this->userRepository->findByUsername($parsedData['username']);

        if (empty($user) || !password_verify($parsedData['password'], $user->getPassword())) {
            return $this->jsonResponse($response, [
                'message' => 'Woops something went wrong'
            ], 403);
        }

        $token = $this->tokenService->createJwt([
            'uid' => $user->getId(),
        ]);

        $lifetime = $this->tokenService->getLifetime();

        return $this->jsonResponse($response, [
            'token'   => $token,
            'expires' => $lifetime
        ], 200);
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response Returns a Response with the given Data
     * @throws \Exception
     */
    public function register(Request $request, Response $response): Response
    {
        $parsedData = $request->getParsedBody();

        $validation = $this->validationService->execute($parsedData, [
            'username' => v::noWhitespace()->notEmpty()->notBlank()->stringType(),
            'mail'     => v::noWhitespace()->notEmpty()->notBlank()->email(),
            'password' => v::notEmpty()->notBlank()->stringType()
        ]);

        if ($validation->failed()) {
            return $this->jsonResponse($response, [
                'message' => 'Please check your provided data'
            ], 422);
        }

        $user = $this->userRepository->findByUsername($parsedData['username']);

        if (!is_null($user)) {
            return $this->jsonResponse($response, [
                'message' => 'Username already exists'
            ], 422);
        }

        $data = [
            'username'          => $parsedData['username'],
            'password'          => $parsedData['password'],
            'mail'              => $parsedData['mail'],
            'credits'           => $this->config->get('hotel_settings.credits'),
            'points'            => $this->config->get('hotel_settings.credits'),
            'pixels'            => $this->config->get('hotel_settings_pixels'),
            'motto'             => $this->config->get('hotel_settings_motto'),
            'ip_register'       => $this->determineIp(),
            'ip_current'        => $this->determineIp(),
            'account_created'   => time(),
            'auth_ticket'       => null
        ];

        $user = $this->userRepository->create($data);

        $token = $this->tokenService->createJwt([
            'uid' => $user->getId(),
        ]);

        $lifetime = $this->tokenService->getLifetime();

        return $this->jsonResponse($response, [
            'token'   => $token,
            'expires' => $lifetime
        ], 200);
    }

    /**
     * @TODO TBD - Need to write something that unvalidates the token...
     *
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response Returns a Response with the given Data
     */
    public function logout(Request $request, Response $response): Response
    {

    }
}
