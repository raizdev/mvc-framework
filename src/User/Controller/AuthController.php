<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\User\Exception\RegisterException;
use Ares\User\Repository\UserRepository;
use Ares\Framework\Service\ValidationService;
use Ares\User\Service\Auth\LoginService;
use Ares\User\Service\Auth\RegisterService;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class AuthController
 *
 * @package Ares\User\Controller\Auth
 */
class AuthController extends BaseController
{
    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var LoginService
     */
    private LoginService $loginService;

    /**
     * @var RegisterService
     */
    private RegisterService $registerService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * AuthController constructor.
     *
     * @param ValidationService $validationService
     * @param LoginService      $loginService
     * @param RegisterService   $registerService
     * @param UserRepository    $userRepository
     */
    public function __construct(
        ValidationService $validationService,
        LoginService $loginService,
        RegisterService $registerService,
        UserRepository $userRepository
    ) {
        $this->validationService = $validationService;
        $this->loginService = $loginService;
        $this->registerService = $registerService;
        $this->userRepository = $userRepository;
    }

    /**
     * Logs the User in and parses a generated Token into response
     *
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
            'username' => 'required',
            'password' => 'required'
        ]);

        $customResponse = $this->loginService->login($parsedData['username'], $parsedData['password']);

        return $this->respond($response, $customResponse);
    }

    /**
     * Registers the User and parses a generated Token into the response
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     * @throws Exception
     */
    public function register(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'username' => 'required|min:3',
            'mail' => 'required|email|min:9',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);

        $parsedData['ip_register'] = $this->determineIp();
        $parsedData['ip_current'] = $this->determineIp();

        $customResponse = $this->registerService->register($parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws RegisterException
     */
    public function check(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $user = $this->userRepository->getBy($body);

        if (is_null($user)) {
            return $response;
        }

        throw new RegisterException(__('general.entity.exists'), 422);
    }

    /**
     * Returns a response without the Authorization header
     * We could blacklist the token with redis-cache
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response Returns a Response with the given Data
     */
    public function logout(Request $request, Response $response): Response
    {
        return $response->withoutHeader('Authorization');
    }
}
