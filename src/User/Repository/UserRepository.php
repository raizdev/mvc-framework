<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\User\Entity\User;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class UserRepository
 *
 * @package Ares\User\Repository
 */
class UserRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_USER_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_USER_COLLECTION_';

    /** @var string */
    protected string $entity = User::class;
}
