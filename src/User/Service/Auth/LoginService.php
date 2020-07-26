<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Auth;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Service\TokenService;
use Ares\User\Entity\User;
use Ares\User\Exception\LoginException;
use Ares\User\Repository\UserRepository;
use ReallySimpleJWT\Exception\ValidateException;

/**
 * Class LoginService
 *
 * @package Ares\User\Service\Auth
 */
class LoginService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * LoginService constructor.
     *
     * @param UserRepository $userRepository
     * @param TokenService $tokenService
     */
    public function __construct(
        UserRepository $userRepository,
        TokenService $tokenService
    ) {
        $this->userRepository = $userRepository;
        $this->tokenService = $tokenService;
    }

    /**
     * Login user.
     *
     * @param string $username
     * @param string $password
     *
     * @return CustomResponseInterface
     * @throws LoginException|ValidateException
     */
    public function login(string $username, string $password): CustomResponseInterface
    {
        /** @var User $user */
        $user = $this->userRepository->getByUsername($username);

        if (empty($user) || !password_verify($password, $user->getPassword())) {
            throw new LoginException(__('general.failed'), 403);
        }

        /** @var TokenService $token */
        $token = $this->tokenService->execute($user->getId());

        return response()->setData([
            'token' => $token
        ]);
    }
}

