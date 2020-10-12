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
use Ares\Role\Repository\RoleUserRepository;
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
     * AssignUserToRoleService constructor.
     *
     * @param RoleUserRepository $roleUserRepository
     */
    public function __construct(
        RoleUserRepository $roleUserRepository
    ) {
        $this->roleUserRepository = $roleUserRepository;
    }

    /**
     * @param Role $role
     * @param int  $userId
     *
     * @return CustomResponseInterface
     * @throws RoleException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(Role $role, int $userId): CustomResponseInterface
    {
        $roleUser = $this->getNewRoleUser($role, $userId);

        $isRoleAlreadyAssigned = $this->roleUserRepository->getOneBy([
            'roleId' => $role->getId(),
            'userId' => $userId
        ]);

        if ($isRoleAlreadyAssigned) {
            throw new RoleException(__('There is already a Role assigned to that User'));
        }

        $this->roleUserRepository->save($roleUser);

        return response()
            ->setData(true);
    }

    /**
     * @param Role $role
     * @param int  $userId
     *
     * @return RoleUser
     */
    private function getNewRoleUser(Role $role, int $userId): RoleUser
    {
        $roleUser = new RoleUser();

        $roleUser
            ->setUserId($userId)
            ->setRole($role);

        return $roleUser;
    }
}
