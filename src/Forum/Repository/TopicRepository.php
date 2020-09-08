<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Repository;

use Ares\Forum\Entity\Topic;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class TopicRepository
 *
 * @package Ares\Forum\Repository
 */
class TopicRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_FORUM_TOPIC_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_FORUM_TOPIC_COLLECTION_';

    /** @var string */
    protected string $entity = Topic::class;
}