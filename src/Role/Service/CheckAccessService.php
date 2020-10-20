<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\CacheException;
use Ares\Role\Entity\Permission;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Repository\RoleUserRepository;

/**
 * Class CheckAccessService
 *
 * @package Ares\Role\Service
 */
class CheckAccessService
{
    /**
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * @var RoleUserRepository
     */
    private RoleUserRepository $roleUserRepository;

    /**
     * @var RoleHierarchyRepository
     */
    private RoleHierarchyRepository $roleHierarchyRepository;

    /**
     * @var RolePermissionRepository
     */
    private RolePermissionRepository $rolePermissionRepository;

    /**
     * CheckAccessService constructor.
     *
     * @param PermissionRepository     $permissionRepository
     * @param RoleUserRepository       $roleUserRepository
     * @param RoleHierarchyRepository  $roleHierarchyRepository
     * @param RolePermissionRepository $rolePermissionRepository
     */
    public function __construct(
        PermissionRepository $permissionRepository,
        RoleUserRepository $roleUserRepository,
        RoleHierarchyRepository $roleHierarchyRepository,
        RolePermissionRepository $rolePermissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->roleUserRepository = $roleUserRepository;
        $this->roleHierarchyRepository = $roleHierarchyRepository;
        $this->rolePermissionRepository = $rolePermissionRepository;
    }

    /**
     * @param int         $userId
     * @param string|null $permissionName
     *
     * @return bool
     * @throws CacheException
     */
    public function execute(int $userId, ?string $permissionName): bool
    {
        $searchCriteria = $this->permissionRepository
            ->getDataObjectManager()
            ->where('name', $permissionName);

        /** @var Permission $permission */
        $permission = $this->permissionRepository
            ->getList($searchCriteria)
            ->first();

        // When there's no permission set, set anonymous(logged in) access
        if (!$permission) {
            return true;
        }

        $roleIds = $this->roleUserRepository->getUserRoleIds($userId);

        if (count($roleIds) > 0) {
            $allRoleIds = $this->roleHierarchyRepository
                ->getAllRoleIdsHierarchy($roleIds);

            return $this->rolePermissionRepository
                ->isPermissionAssigned(
                    $permission->getId(),
                    $allRoleIds
                );
        }

        return false;
    }
}
