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
    protected string $cachePrefix = 'ARES_FORUM_TOPIC_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_FORUM_TOPIC_COLLECTION_';

    /** @var string */
    protected string $entity = Topic::class;
}
