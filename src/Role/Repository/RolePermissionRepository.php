<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\RolePermission;

/**
 * Class RolePermissionRepository
 *
 * @package Ares\Role\Repository
 */
class RolePermissionRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_ROLE_ROLE_PERMISSION_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_ROLE_ROLE_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = RolePermission::class;
}