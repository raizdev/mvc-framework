<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\RoleHierarchy;

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
}