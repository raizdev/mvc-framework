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
    protected string $cachePrefix = 'ARES_ROLE_PERMISSION';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = Permission::class;
}
