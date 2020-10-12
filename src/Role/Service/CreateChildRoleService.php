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
use Ares\Role\Repository\RoleRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\QueryException;
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
     * @var RoleRepository
     */
    private RoleRepository $roleRepository;

    /**
     * CreateChildRoleService constructor.
     *
     * @param RoleHierarchyRepository $roleHierarchyRepository
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        RoleHierarchyRepository $roleHierarchyRepository,
        RoleRepository $roleRepository
    ) {
        $this->roleHierarchyRepository = $roleHierarchyRepository;
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
        /** @var int $parentRoleId */
        $parentRoleId = $data['parent_role_id'];

        /** @var int $childRoleId */
        $childRoleId = $data['child_role_id'];

        $isCycle = $this->checkForCycle($parentRoleId, $childRoleId);

        if ($isCycle) {
            throw new RoleException(__('Cycle detected for given Role notations'));
        }

        /** @var Role $parentRole */
        $parentRole = $this->roleRepository->get($parentRoleId);

        /** @var Role $childRole */
        $childRole = $this->roleRepository->get($childRoleId);

        if (!$parentRole || !$childRole) {
            throw new RoleException(__('Could not found given Roles'));
        }

        $newChildRole = $this->getNewChildRole($parentRole, $childRole);

        $this->roleHierarchyRepository->save($newChildRole);

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

    /**
     * @param int $parentRoleId
     * @param int $childRoleId
     *
     * @return bool
     * @throws QueryException
     */
    private function checkForCycle(int $parentRoleId, int $childRoleId): bool
    {
        $hasChildRole = $this->roleHierarchyRepository->hasChildRoleId($parentRoleId, $childRoleId);

        if (!$hasChildRole) {
            return false;
        }

        return true;
    }
}
