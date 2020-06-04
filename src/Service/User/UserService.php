<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Service\User;


use App\Repository\User\UserRepository;
use App\Service\BaseService;

class UserService extends BaseService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserService constructor.
     *
     * @param   UserRepository  $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @return object|null
     */
    public function fetchAll(): ?array
    {
        return $this->userRepository->all();
    }
}