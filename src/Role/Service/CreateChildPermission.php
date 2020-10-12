<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Entity\Permission;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RolePermission;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RolePermissionRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateChildPermission
 *
 * @package Ares\Role\Service
 */
class CreateChildPermission
{
    /**
     * @var RolePermissionRepository
     */
    private RolePermissionRepository $rolePermissionRepository;

    /**
     * CreateChildPermission constructor.
     *
     * @param RolePermissionRepository $rolePermissionRepository
     */
    public function __construct(
        RolePermissionRepository $rolePermissionRepository
    ) {
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    /**
     * @param Role       $role
     * @param Permission $permission
     *
     * @return CustomResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoleException
     * @throws InvalidArgumentException
     */
    public function execute(Role $role, Permission $permission): CustomResponseInterface
    {
        $childPermission = $this->getNewChildPermission($role, $permission);

        $existingChildPermission = $this->rolePermissionRepository->getOneBy([
            'role' => $role,
            'permission' => $permission
        ]);

        if ($existingChildPermission) {
            throw new RoleException(__('There is already a Permission assigned to that Role'));
        }

        $this->rolePermissionRepository->save($childPermission);

        return response()
            ->setData(true);
    }

    /**
     * @param Role       $role
     * @param Permission $permission
     *
     * @return RolePermission
     */
    private function getNewChildPermission(Role $role, Permission $permission): RolePermission
    {
        $rolePermission = new RolePermission();

        $rolePermission
            ->setRole($role)
            ->setPermission($permission);

        return $rolePermission;
    }
}
