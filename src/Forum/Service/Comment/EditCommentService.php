<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Service\Comment;

use Ares\Forum\Entity\Comment;
use Ares\Forum\Exception\CommentException;
use Ares\Forum\Repository\CommentRepository;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class EditCommentService
 *
 * @package Ares\Forum\Service\Comment
 */
class EditCommentService
{
    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * EditCommentService constructor.
     *
     * @param   CommentRepository  $commentRepository
     */
    public function __construct(
        CommentRepository $commentRepository
    ) {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return CustomResponseInterface
     * @throws CommentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        /** @var int $comment_id */
        $comment_id = $data['comment_id'];

        /** @var string $content */
        $content = $data['content'];

        /** @var Comment $comment */
        $comment = $this->commentRepository->get($comment_id);

        if (!$comment) {
            throw new CommentException(__('Comment not found'));
        }

        $comment
            ->setContent($content)
            ->setIsEdited(1);

        $thread = $this->commentRepository->update($comment);

        return response()->setData($thread->toArray());
    }
}