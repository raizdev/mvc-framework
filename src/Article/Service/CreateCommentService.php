<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Comment;
use Ares\Article\Exception\CommentException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateCommentService
 *
 * @package Ares\Article\Service
 */
class CreateCommentService
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
     * CreateCommentService constructor.
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
     * Creates new comment.
     *
     * @param User $user
     * @param array $data
     * @return CustomResponseInterface
     * @throws CommentException
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        $comment = $this->getNewComment($user, $data);

        $comment = $this->commentRepository->save($comment);

        return response()->setData($comment);
    }

    /**
     * Returns new comment object with data.
     *
     * @param User $user
     * @param array $data
     * @return Comment
     * @throws CommentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    private function getNewComment(User $user, array $data): Comment
    {
        $comment = new Comment();

        $article = $this->articleRepository->get($data['article_id'], false);

        if (!$article) {
            throw new CommentException(__('Related article was not found.'), 404);
        }

        return $comment
            ->setContent($data['content'])
            ->setIsEdited(0)
            ->setUser($user)
            ->setLikes(0)
            ->setDislikes(0)
            ->setArticle($article)
            ->setLikes(0)
            ->setDislikes(0);
    }
}
