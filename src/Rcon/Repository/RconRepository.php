<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Rcon\Entity\Rcon;

/**
 * Class RconRepository
 *
 * @package Ares\Rcon\Repository
 */
class RconRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_RCON_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_RCON_COLLECTION_';

    /** @var string */
    protected string $entity = Rcon::class;
}
