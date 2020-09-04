<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Service;

use Ares\Article\Repository\ArticleRepository;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Entity\Entity;
use Ares\Framework\Repository\BaseRepository;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Interfaces\VoteEntityInterface;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class GetVoteEntityService
 *
 * @package Ares\Vote\Service
 */
class GetVoteEntityService
{
    /**
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;

    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * GetVoteEntityService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Takes id and entity and loads specific entity.
     *
     * @param int $id
     * @param int $entity
     * @return BaseRepository|null
     * @throws VoteException
     */
    public function execute(int $id, int $entity): BaseRepository
    {
        switch (true) {
            case $entity === VoteEntityInterface::ARTICLE_VOTE_ENTITY:
                return $this->articleRepository;
            case $entity === VoteEntityInterface::ARTICLE_COMMENT_VOTE_ENTITY:
                return $this->commentRepository;
//            case $entity === VoteEntityInterface::FORUM_VOTE_ENTITY:
//                return ;
//            case $entity === VoteEntityInterface::FORUM_COMMENT_VOTE_ENTITY:
//                return ;
//            case $entity === VoteEntityInterface::GUESTBOOK_VOTE_ENTITY:
//                return ;
            default:
                throw new VoteException(__('Entity with id %s does not exist.', [$entity]), 404);
        }
    }
}