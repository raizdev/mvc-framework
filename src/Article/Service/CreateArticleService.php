<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Article;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;

/**
 * Class CreateArticleService
 *
 * @package Ares\Article\Service
 */
class CreateArticleService
{
    /**
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;

    /**
     * CreateArticleService constructor.
     *
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Creates new article with given data.
     *
     * @param User $user
     * @param array $data
     * @return CustomResponseInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        $article = $this->getNewArticle($user, $data);

        $existingArticle = $this->articleRepository->findOneBy(['slug' => $article->getSlug()]);

        if ($existingArticle) {
            throw new ArticleException(__('Article with given slug already exists.'), 422);
        }

        $article = $this->articleRepository->save($article);

        return response()->setData($article);
    }

    /**
     * Returns new article.
     *
     * @param User $user
     * @param array $data
     * @return Article
     */
    private function getNewArticle(User $user, array $data): Article
    {
        $article = new Article();

        return $article
            ->setTitle($data['title'])
            ->setSlug($data['slug'])
            ->setDescription($data['description'])
            ->setContent($data['content'])
            ->setImage($data['image'])
            ->setAuthor($user)
            ->setHidden($data['hidden'])
            ->setPinned($data['pinned'])
            ->setLikes(0)
            ->setDislikes(0);
    }
}