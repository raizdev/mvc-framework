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
    protected const CACHE_PREFIX = 'ARES_VOTE_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_VOTE_COLLECTION_';

    /** @var string */
    protected string $entity = Vote::class;
}