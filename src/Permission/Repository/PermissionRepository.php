<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Permission\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Permission\Entity\Permission;

/**
 * Class PermissionRepository
 *
 * @package Ares\Permission\Repository
 */
class PermissionRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_PERMISSION_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = Permission::class;
}
