<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Service\Auth;

use App\Entity\User;
use App\Repository\User\UserRepository;
use App\Service\BaseService;
use Doctrine\ORM\ORMException;

/**
 * Class AuthService
 */
class AuthService extends BaseService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function login()
    {
    }

    public function logout()
    {
    }

    /**
     * @param   array  $data
     *
     * @return User
     * @throws ORMException
     */
    public function register(array $data): User
    {
        return $this->userRepository->create($data);
    }
}