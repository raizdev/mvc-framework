<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Vote\Entity\Vote;

/**
 * Class VoteRepository
 *
 * @package Ares\Vote\Repository
 */
class VoteRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_VOTE_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_VOTE_COLLECTION_';

    /** @var string */
    protected string $entity = Vote::class;
}
