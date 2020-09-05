<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Service\Comment;

use Ares\Forum\Entity\Comment;
use Ares\Forum\Entity\Thread;
use Ares\Forum\Exception\CommentException;
use Ares\Forum\Repository\CommentRepository;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateCommentService
 *
 * @package Ares\Forum\Service\Comment
 */
class CreateCommentService
{
    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * @var ThreadRepository
     */
    private ThreadRepository $threadRepository;

    /**
     * CreateCommentService constructor.
     *
     * @param   CommentRepository  $commentRepository
     * @param   ThreadRepository   $threadRepository
     */
    public function __construct(
        CommentRepository $commentRepository,
        ThreadRepository $threadRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->threadRepository = $threadRepository;
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return CustomResponseInterface
     * @throws CommentException
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        $comment = $this->getNewComment($user, $data);

        $comment = $this->commentRepository->save($comment);

        return response()->setData($comment);
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return Comment
     * @throws CommentException
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getNewComment(User $user, array $data): Comment
    {
        $comment = new Comment();

        /** @var Thread $thread */
        $thread = $this->threadRepository->get($data['thread_id']);

        if (!$thread) {
            throw new CommentException(__('Related Thread was not found.'), 404);
        }

        return $comment
            ->setThread($thread)
            ->setUser($user)
            ->setContent($data['content'])
            ->setIsEdited(0)
            ->setLikes(0)
            ->setDislikes(0);
    }
}