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
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Repository\RoleRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateChildPermission
 *
 * @package Ares\Role\Service
 */
class CreateRolePermissionService
{
    /**
     * @var RolePermissionRepository
     */
    private RolePermissionRepository $rolePermissionRepository;

    /**
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * @var RoleRepository
     */
    private RoleRepository $roleRepository;

    /**
     * CreateChildPermission constructor.
     *
     * @param RolePermissionRepository $rolePermissionRepository
     * @param PermissionRepository     $permissionRepository
     * @param RoleRepository           $roleRepository
     */
    public function __construct(
        RolePermissionRepository $rolePermissionRepository,
        PermissionRepository $permissionRepository,
        RoleRepository $roleRepository
    ) {
        $this->rolePermissionRepository = $rolePermissionRepository;
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
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
        /** @var int $roleId */
        $roleId = $data['role_id'];

        /** @var int $permissionId */
        $permissionId = $data['permission_id'];

        /** @var Role $role */
        $role = $this->roleRepository->get($roleId);

        /** @var Permission $permission */
        $permission = $this->permissionRepository->get($permissionId);


        if (!$role || !$permission) {
            throw new RoleException(__('Could not found given Role or Permission'));
        }

        $existingRolePermission = $this->rolePermissionRepository->getOneBy([
            'role' => $role,
            'permission' => $permission
        ]);

        if ($existingRolePermission) {
            throw new RoleException(__('There is already a Permission assigned to that Role'));
        }

        $rolePermission = $this->getNewRolePermission($role, $permission);

        /** @var RolePermission $rolePermission */
        $rolePermission = $this->rolePermissionRepository->save($rolePermission);

        return response()
            ->setData($rolePermission);
    }

    /**
     * @param Role       $role
     * @param Permission $permission
     *
     * @return RolePermission
     */
    private function getNewRolePermission(Role $role, Permission $permission): RolePermission
    {
        $rolePermission = new RolePermission();

        $rolePermission
            ->setRole($role)
            ->setPermission($permission);

        return $rolePermission;
    }
}
