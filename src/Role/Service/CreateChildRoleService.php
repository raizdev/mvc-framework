<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleHierarchy;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleHierarchyRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateChildRoleService
 *
 * @package Ares\Role\Service
 */
class CreateChildRoleService
{
    /**
     * @var RoleHierarchyRepository
     */
    private RoleHierarchyRepository $roleHierarchyRepository;

    /**
     * CreateChildRoleService constructor.
     *
     * @param RoleHierarchyRepository $roleHierarchyRepository
     */
    public function __construct(
        RoleHierarchyRepository $roleHierarchyRepository
    ) {
        $this->roleHierarchyRepository = $roleHierarchyRepository;
    }

    /**
     * @param Role $parentRole
     * @param Role $childRole
     *
     * @return CustomResponseInterface
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoleException
     */
    public function execute(Role $parentRole, Role $childRole): CustomResponseInterface
    {
        $childRole = $this->getNewChildRole($parentRole, $childRole);

        $existingPermission = $this->roleHierarchyRepository->getOneBy([
            'childRole' => $childRole,
            'parentRole' => $parentRole
        ]);

        if ($existingPermission) {
            throw new RoleException(__('There is already a ChildRole with that ParentRole'));
        }

        $this->roleHierarchyRepository->save($childRole);

        return response()
            ->setData(true);
    }

    /**
     * @param Role $parentRole
     * @param Role $childRole
     *
     * @return RoleHierarchy
     */
    private function getNewChildRole(Role $parentRole, Role $childRole): RoleHierarchy
    {
        $roleHierarchy = new RoleHierarchy();

        $roleHierarchy
            ->setParentRole($parentRole)
            ->setChildRole($childRole);

        return $roleHierarchy;
    }
}
