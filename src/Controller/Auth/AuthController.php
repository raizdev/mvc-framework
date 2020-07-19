<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Entity\User;
use App\Repository\User\UserRepository;
use App\Service\TokenService;
use App\Service\ValidationService;
use Exception;
use PHLAK\Config\Config;
use ReallySimpleJWT\Exception\ValidateException;
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
     * @throws ValidateException
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

        /** @var User $user */
        $user = $this->userRepository->findByUsername($parsedData['username']);

        if (empty($user) || !password_verify($parsedData['password'], $user->getPassword())) {
            return $this->jsonResponse($response, [
                'message' => 'Woops something went wrong'
            ], 403);
        }

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        return $this->jsonResponse($response, [
            'code' => 200,
            'token' => $token
        ], 200);
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

        $validation = $this->validationService->execute($parsedData, [
            'username' => v::noWhitespace()->notEmpty()->notBlank()->stringType(),
            'mail' => v::noWhitespace()->notEmpty()->notBlank()->email(),
            'password' => v::notEmpty()->notBlank()->stringType()
        ]);

        if ($validation->failed()) {
            return $this->jsonResponse($response, [
                'message' => 'Please check your provided data'
            ], 422);
        }

        /** @var User $user */
        $user = $this->userRepository->findByUsername($parsedData['username']);

        if (!is_null($user)) {
            return $this->jsonResponse($response, [
                'message' => 'Username already exists'
            ], 422);
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

        $user = $this->userRepository->create($data);

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        return $this->jsonResponse($response, [
            'code' => 200,
            'token' => $token
        ], 200);
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
