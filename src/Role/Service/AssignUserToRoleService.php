<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleUser;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Repository\RoleUserRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class AssignUserToRoleService
 *
 * @package Ares\Role\Service
 */
class AssignUserToRoleService
{
    /**
     * @var RoleUserRepository
     */
    private RoleUserRepository $roleUserRepository;

    /**
     * @var RoleRepository
     */
    private RoleRepository $roleRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * AssignUserToRoleService constructor.
     *
     * @param RoleUserRepository $roleUserRepository
     * @param RoleRepository     $roleRepository
     * @param UserRepository     $userRepository
     */
    public function __construct(
        RoleUserRepository $roleUserRepository,
        RoleRepository $roleRepository,
        UserRepository $userRepository
    ) {
        $this->roleUserRepository = $roleUserRepository;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoleException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $userId */
        $userId = $data['role_id'];

        /** @var int $roleId */
        $roleId = $data['role_id'];

        /** @var Role $role */
        $role = $this->roleRepository->get($userId);

        /** @var User $user */
        $user = $this->userRepository->get($roleId);

        if (!$role || !$user) {
            throw new RoleException(__('Could not find called Role or User'));
        }

        $isRoleAlreadyAssigned = $this->roleUserRepository->getOneBy([
            'roleId' => $roleId,
            'userId' => $userId
        ]);

        if ($isRoleAlreadyAssigned) {
            throw new RoleException(__('There is already a Role assigned to that User'));
        }

        $roleUser = $this->getNewRoleUser($role, $user);

        $this->roleUserRepository->save($roleUser);

        return response()
            ->setData(true);
    }

    /**
     * @param Role $role
     * @param User $user
     *
     * @return RoleUser
     */
    private function getNewRoleUser(Role $role, User $user): RoleUser
    {
        $roleUser = new RoleUser();

        $roleUser
            ->setUser($user)
            ->setRole($role);

        return $roleUser;
    }
}
