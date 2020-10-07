<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\Role;

/**
 * Class RoleRepository
 *
 * @package Ares\Role\Repository
 */
class RoleRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_ROLE_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_ROLE_COLLECTION_';

    /** @var string */
    protected string $entity = Role::class;
}