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
    protected string $cachePrefix = 'ARES_USER_BADGE_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_BADGE_COLLECTION_';

    /** @var string */
    protected string $entity = UserBadge::class;
}
