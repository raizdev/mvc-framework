<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserBadge;

/**
 * Class UserBadgeRepository
 *
 * @package Ares\User\Repository
 */
class UserBadgeRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_USER_BADGE_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_USER_BADGE_COLLECTION_';

    /** @var string */
    protected string $entity = UserBadge::class;

    /**
     * @param $user
     *
     * @return int|mixed|string
     */
    public function getSlotBadges($user): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('b')
            ->from(UserBadge::class, 'b')
            ->andWhere('b.user = ?1')
            ->andWhere('b.slot_id > 1')
            ->orderBy('b.slot_id', 'ASC')
            ->setParameter(1, $user)
            ->getQuery()
            ->getResult();
    }
}