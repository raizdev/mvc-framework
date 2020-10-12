<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\RoleUser;
use Doctrine\ORM\Query\QueryException;

/**
 * Class RoleUserRepository
 *
 * @package Ares\Role\Repository
 */
class RoleUserRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_ROLE_USER_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_ROLE_USER_COLLECTION_';

    /** @var string */
    protected string $entity = RoleUser::class;

    /**
     * @param integer $userId
     * @return array
     * @throws QueryException
     */
    public function getUserRoleIds($userId)
    {
        $qb = $this->createQueryBuilder('userRole');

        $qb->select('userRole.roleId')
            ->where(
                $qb->expr()
                ->eq('userRole.userId', $userId)
            )
            ->indexBy('userRole', 'userRole.roleId');

        $roleIds = $qb->getQuery()
            ->getArrayResult();

        return array_keys($roleIds);
    }
}
