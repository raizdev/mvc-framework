<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Repository;

use Ares\Article\Entity\Comment;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class CommentRepository
 *
 * @package Ares\Article\Repository
 */
class CommentRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_COMMENT_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_COMMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Comment::class;
}