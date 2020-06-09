<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Repository\User\UserRepository;
use App\Service\GenerateTokenService;
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
     * @var GenerateTokenService
     */
    private GenerateTokenService $generateTokenService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * AuthController constructor.
     *
     * @param   GenerateTokenService  $generateTokenService
     * @param   ValidationService     $validationService
     * @param   UserRepository        $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        GenerateTokenService $generateTokenService,
        ValidationService $validationService
    ) {
        $this->userRepository       = $userRepository;
        $this->generateTokenService = $generateTokenService;
        $this->validationService    = $validationService;
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

        if (empty($user) || !password_verify($parsedData['password'], $user->password)) {
            return $this->jsonResponse($response, [
                'message' => 'Woops something went wrong'
            ], 403);
        }

        $tokenInfo = $this->generateTokenService->execute($user->id);

        return $this->jsonResponse($response, [
            'message' => $user,
            'token'   => $tokenInfo['token'],
            'expires' => $tokenInfo['expire']
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

        $data = [
            'username' => $parsedData['username'],
            'password' => $parsedData['password'],
            'mail'     => $parsedData['mail'],
        ];

        $user      = $this->userRepository->create($data);
        $tokenInfo = $this->generateTokenService->execute($user->getId());

        return $this->jsonResponse($response, [
            'message' => $user,
            'token'   => $tokenInfo['token'],
            'expires' => $tokenInfo['expire']
        ], 200);
    }
}