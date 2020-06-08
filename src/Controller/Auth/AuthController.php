<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Service\Auth\AuthService;
use App\Service\Auth\GenerateTokenService;
use App\Validation\Validator;
use Respect\Validation\Validator as v;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class AuthController
 */
class AuthController extends BaseController
{
    /**
     * @var Validator
     */
    private Validator $validator;

    /**
     * @var AuthService
     */
    private AuthService $authService;

    /**
     * @var GenerateTokenService
     */
    private GenerateTokenService $generateTokenService;

    /**
     * AuthController constructor.
     *
     * @param   AuthService           $authService
     * @param   GenerateTokenService  $generateTokenService
     * @param   Validator             $validator
     */
    public function __construct(
        AuthService $authService,
        GenerateTokenService $generateTokenService,
        Validator $validator
    ) {
        $this->authService          = $authService;
        $this->generateTokenService = $generateTokenService;
        $this->validator            = $validator;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     */
    public function login(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'username' => v::noWhitespace()->notEmpty(),
            'password' => v::notEmpty()
        ]);

        if ($validation->failed()) {
            return $this->jsonResponse($response, [
                'message' => 'Please check your provided data'
            ], 422);
        }

        $token = $this->authService->login();

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

        $validation = $this->validator->validate($parsedData, [
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

        $user  = $this->authService->register($data);
        $token = $this->generateTokenService->execute($user);

        return $this->jsonResponse($response, [
            'message' => $user,
            'token'   => $token,
        ], 200);
    }
}