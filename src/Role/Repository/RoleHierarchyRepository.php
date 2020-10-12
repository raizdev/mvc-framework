<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\RoleHierarchy;
use Doctrine\ORM\Query\QueryException;

/**
 * Class RoleHierarchyRepository
 *
 * @package Ares\Role\Repository
 */
class RoleHierarchyRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_ROLE_HIERARCHY_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_ROLE_HIERARCHY_COLLECTION_';

    /** @var string */
    protected string $entity = RoleHierarchy::class;

    /**
     * @param array $parentIds
     *
     * @return array
     * @throws QueryException
     */
    private function getChildIds(array $parentIds): array
    {
        $qb = $this->createQueryBuilder('rh');

        $qb->select('rh.childRoleId')
            ->where($qb->expr()->in( 'rh.parentRoleId', $parentIds))
            ->indexBy('rh', 'rh.childRoleId');

        $childRoleIds =  $qb->getQuery()
            ->getArrayResult();

        return array_keys($childRoleIds);
    }

    /**
     * @param array $parentIds
     *
     * @return array
     * @throws QueryException
     */
    private function getAllChildRoleIds(array $parentIds): array
    {
        $allChildIds = [];

        while (count($parentIds) > 0) {
            $parentIds = $this->getChildIds($parentIds);
            $allChildIds = array_merge($allChildIds, $parentIds);
        };

        return $allChildIds;
    }

    /**
     * @param int $parentRoleId
     * @param int $findingChildId
     *
     * @return bool
     * @throws QueryException
     */
    public function hasChildRoleId(int $parentRoleId, int $findingChildId): bool
    {
        $childIds = $this->getChildIds([$parentRoleId]);

        if (count($childIds) > 0) {

            if (in_array($findingChildId, $childIds))
                return true;

            foreach ($childIds as $childId) {
                if ($this->hasChildRoleId($childId, $findingChildId) == true) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array $rootRoleIds
     *
     * @return array
     * @throws QueryException
     */
    public function getAllRoleIdsHierarchy(array $rootRoleIds): array
    {
        $childRoleIds = $this->getAllChildRoleIds($rootRoleIds);

        return array_merge($rootRoleIds, $childRoleIds);
    }
}
