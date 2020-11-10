<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RoleUserRepository;

/**
 * Class FetchUserPermissionService
 *
 * @package Ares\Role\Service
 */
class FetchUserPermissionService
{
    /**
     * @var RoleUserRepository
     */
    private RoleUserRepository $roleUserRepository;

    /**
     * @var RoleHierarchyRepository
     */
    private RoleHierarchyRepository $roleHierarchyRepository;

    /**
     * FetchUserPermissionService constructor.
     *
     * @param RoleUserRepository      $roleUserRepository
     * @param RoleHierarchyRepository $roleHierarchyRepository
     */
    public function __construct(
        RoleUserRepository $roleUserRepository,
        RoleHierarchyRepository $roleHierarchyRepository
    ) {
        $this->roleUserRepository = $roleUserRepository;
        $this->roleHierarchyRepository = $roleHierarchyRepository;
    }

    /**
     * @param int $userId
     *
     * @return CustomResponseInterface
     */
    public function execute(int $userId): CustomResponseInterface
    {
        /** @var array $userRoleIds */
        $userRoleIds = $this->roleUserRepository->getUserRoleIds($userId);

        if (!$userRoleIds) {
            return response()
                ->setData([]);
        }

        /** @var array $allRoleIds */
        $allRoleIds = $this->roleHierarchyRepository
            ->getAllRoleIdsHierarchy($userRoleIds);

        /** @var array $permissions */
        $permissions = $this->roleUserRepository->getUserPermissions($allRoleIds);

        return response()
            ->setData($permissions);
    }
}
