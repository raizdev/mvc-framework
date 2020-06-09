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
use App\Validation\ValidationService;
use League\Container\Container;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class AuthController
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
     * @param   Container             $container
     * @param   GenerateTokenService  $generateTokenService
     * @param   ValidationService     $validationService
     * @param   UserRepository        $userRepository
     */
    public function __construct(
        Container $container,
        GenerateTokenService $generateTokenService,
        ValidationService $validationService,
        UserRepository $userRepository
    ) {
        parent::__construct($container);
        $this->generateTokenService = $generateTokenService;
        $this->validationService    = $validationService;
        $this->userRepository       = $userRepository;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     */
    public function login(Request $request, Response $response): Response
    {
        $validation = $this->validationService->validate($request, [
            'username' => v::noWhitespace()->notEmpty(),
            'password' => v::notEmpty()
        ]);

        if ($validation->failed()) {
            return $this->jsonResponse($response, [
                'message' => 'Please check your provided data'
            ], 422);
        }

        $token = '123';

        return $this->jsonResponse($response, [
            'message' => 'Successfully logged in',
            'token'   => $token
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

        $validation = $this->validationService->validate($parsedData, [
            'username' => v::noWhitespace()->notEmpty()->notBlank(),
            'mail'     => v::noWhitespace()->notEmpty()->notBlank()->email(),
            'password' => v::notEmpty()->notBlank()
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

        $user  = $this->userRepository->create($data);
        $token = $this->generateTokenService->execute($user);

        return $this->jsonResponse($response, [
            'message' => $user,
            'token'   => $token,
        ], 200);
    }
}