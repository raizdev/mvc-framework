<?php declare(strict_types=1);

namespace Raizdev\User\Controller;

use Raizdev\User\Model\UserModel;
use Raizdev\User\Exception\RegisterException;

use Raizdev\Framework\Mapping\Annotation as AR;
use Raizdev\Framework\Controller\BaseController;
use Raizdev\Framework\Service\ValidationService;
use Raizdev\Framework\Service\TokenService;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Raizdev\Framework\Config;

/**
* Class AuthController
*
* @AR\Router
* @AR\Group(
*     prefix="auth",
*     pattern="auth",
* )
*/
class AuthController extends BaseController
{
    public function __construct(
        private ValidationService   $validationService,
        private TokenService        $tokenService,
        private UserModel           $userModel,
        private Config              $config,
    ) {}

    /**
     * Logs the User in and parses a generated Token into response
     * 
     * @AR\Route(
     *     methods={"POST"},
     *     pattern="/sign-in"
     * )
     */
    public function login(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $data = $request->getParsedBody();

        $user = $this->userModel->firstWhere('username', $data['username']);

        if (!$user || !password_verify($data['password'], $user->password)) {
            throw new RegisterException(
                __('User credentials not right!'), 401, 401
            );
        }

        $token = $this->tokenService->execute($user->id);

        return $this->respond(
            $response,
            response()->setData([
                'user'  => $user,
                'token' => $token
            ])
        );
    }

    /**
     * Registers the User and parses a generated Token into the response
     *
     * @AR\Route(
     *     methods={"POST"},
     *     pattern="/registration"
     * )
     * 
     */
    public function register(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $data = $request->getParsedBody();

        $this->validationService->validate($data, [
            'username'              => 'required|min:2|max:12|regex:/^[a-zA-Z\-\=\!\?\@\:\,\.\d]+$/',
            'email'                 => 'required|email|min:9',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);  

        $user = $this->userModel->where('username', $data['username'])->orWhere('email', $data['email'])->get();

        if($user->isNotEmpty()) {
            throw new RegisterException(
                __('User already exists!'), 401, 401
            );
        }

        $data['password'] = $this->hash($data['password']);

        $user = $this->userModel->create($data);

        $token = $this->tokenService->execute($user->id);

        return $this->respond(
            $response,
            response()->setData([
                'user'  => $user,
                'token' => $token
            ])
        );
    }

    /**
     * Returns a response without the Authorization header
     * We could blacklist the token with redis-cache
     */
    public function logout(Request $request, Response $response): Response
    {
        return $response->withoutHeader('Authorization');
    }

    /**
     * Logs the User in and parses a generated Token into response
     * 
     * @AR\Route(
     *     methods={"GET"},
     *     pattern="/me"
     * )
     * 
     */
    public function me(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $data = $request->getParsedBody();

        $user = user($request);

        return $this->respond(
            $response,
            response()->setData([
                'user' => $user
            ])
        );
    }

    public function hash(string $password): string
    {
        return password_hash(
            $password,
            json_decode(
                $this->config->get('api_settings.password.algorithm')
            ) ?? PASSWORD_ARGON2ID, [
                'memory_cost' => $this->config->get('api_settings.password.memory_cost'),
                'time_cost' => $this->config->get('api_settings.password.time_cost'),
                'threads' => $this->config->get('api_settings.password.threads')
            ]
        );
    }
}
