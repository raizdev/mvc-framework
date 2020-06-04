<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Service\Auth\AuthService;
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
     * AuthController constructor.
     *
     * @param   AuthService  $authService
     * @param   Validator    $validator
     */
    public function __construct(
        AuthService $authService,
        Validator $validator
    ) {
        $this->authService = $authService;
        $this->validator   = $validator;
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

        $token = $this->authService->performLogin();

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
     */
    public function register(Request $request, Response $response): Response
    {
        $validation = $this->validator->validate($request, [
            'username'              => v::noWhitespace()->notEmpty()->unique()->notBlank(),
            'mail'                  => v::noWhitespace()->notEmpty()->unique()->notBlank()->email(),
            'password'              => v::notEmpty()->notBlank(),
            'password_confirmation' => v::notEmpty()->notBlank()->equals($request->getAttribute('password_confirmation'))
        ]);

        if ($validation->failed()) {
            return $this->jsonResponse($response, [
                'message' => 'Please check your provided data'
            ], 422);
        }

        $data = [
            'username' => $request->getAttribute('username'),
            'password' => $request->getAttribute('password'),
            'mail'     => $request->getAttribute('mail'),
        ];

        $token = $this->authService->performRegister($data);

        return $this->jsonResponse($response, [
            'message' => 'Successfully logged in',
            'token'   => $token
        ], 200);
    }
}