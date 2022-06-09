<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see       LICENSE (MIT)
 */

namespace Ares\Vote\Service;

use Ares\Article\Repository\ArticleRepository;
use Ares\Article\Repository\CommentRepository;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\Forum\Repository\CommentRepository as ThreadCommentRepository;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\Photo\Repository\PhotoRepository;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Interfaces\Response\VoteResponseCodeInterface;
use Ares\Vote\Interfaces\VoteEntityInterface;

/**
 * Class GetVoteEntityService
 *
 * @package Ares\Vote\Service
 */
class GetVoteEntityService
{
    /**
     * GetVoteEntityService constructor.
     *
     * @param ArticleRepository       $articleRepository
     * @param CommentRepository       $commentRepository
     * @param ThreadRepository        $threadRepository
     * @param ThreadCommentRepository $threadCommentRepository
     * @param GuestbookRepository     $guestbookRepository
     * @param PhotoRepository         $photoRepository
     */
    public function __construct(
        private readonly ArticleRepository       $articleRepository,
        private readonly CommentRepository       $commentRepository,
        private readonly ThreadRepository        $threadRepository,
        private readonly ThreadCommentRepository $threadCommentRepository,
        private readonly GuestbookRepository     $guestbookRepository,
        private readonly PhotoRepository $photoRepository
    ) {}

    /**
     * Takes id and entity and loads specific entity.
     *
     * @param int $entity
     *
     * @return BaseRepository
     * @throws VoteException
     */
    public function execute(int $entity): BaseRepository
    {
        return match (true) {
            $entity === VoteEntityInterface::ARTICLE_VOTE_ENTITY => $this->articleRepository,
            $entity === VoteEntityInterface::ARTICLE_COMMENT_VOTE_ENTITY => $this->commentRepository,
            $entity === VoteEntityInterface::FORUM_VOTE_ENTITY => $this->threadRepository,
            $entity === VoteEntityInterface::FORUM_COMMENT_VOTE_ENTITY => $this->threadCommentRepository,
            $entity === VoteEntityInterface::GUESTBOOK_VOTE_ENTITY => $this->guestbookRepository,
            $entity === VoteEntityInterface::PHOTO_VOTE_ENTITY => $this->photoRepository,
            default => throw new VoteException(
                __('Entity with id %s does not exist',
                    [$entity]),
                VoteResponseCodeInterface::RESPONSE_VOTE_ENTITY_NOT_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_NOT_FOUND
            ),
        };
    }
}
