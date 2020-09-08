<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Ban\Repository;

use Ares\Ban\Entity\Ban;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class BanRepository
 *
 * @package Ares\Ban\Repository
 */
class BanRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_BAN_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_BAN_COLLECTION_';

    /** @var string */
    protected string $entity = Ban::class;
}
