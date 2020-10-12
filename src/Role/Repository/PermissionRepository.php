<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\Permission;

/**
 * Class PermissionRepository
 *
 * @package Ares\Role\Repository
 */
class PermissionRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_ROLE_PERMISSION';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_ROLE_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = Permission::class;
}
