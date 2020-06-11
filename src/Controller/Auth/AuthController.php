<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Repository\User\UserRepository;
use App\Service\TokenService;
use App\Service\ValidationService;
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
     * AuthController constructor.
     *
     * @param   UserRepository     $userRepository
     * @param   ValidationService  $validationService
     * @param   TokenService       $tokenService
     */
    public function __construct(
        UserRepository $userRepository,
        ValidationService $validationService,
        TokenService $tokenService
    ) {
        $this->userRepository    = $userRepository;
        $this->validationService = $validationService;
        $this->tokenService      = $tokenService;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
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
     * @return Response
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
            'username' => $parsedData['username'],
            'password' => $parsedData['password'],
            'mail'     => $parsedData['mail'],
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
     * @return Response
     */
    public function logout(Request $request, Response $response): Response
    {

    }
}