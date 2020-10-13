<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Repository;

use Ares\Forum\Entity\Comment;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class CommentRepository
 *
 * @package Ares\Forum\Repository
 */
class CommentRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_FORUM_COMMENT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_FORUM_COMMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Comment::class;
}
