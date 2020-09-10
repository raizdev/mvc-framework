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
use Cocur\Slugify\Slugify;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

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
     * @var Slugify
     */
    private Slugify $slug;

    /**
     * CreateArticleService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param Slugify           $slug
     */
    public function __construct(
        ArticleRepository $articleRepository,
        Slugify $slug
    ) {
        $this->articleRepository = $articleRepository;
        $this->slug = $slug;
    }

    /**
     * Creates new article with given data.
     *
     * @param User  $user
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws ArticleException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        $article = $this->getNewArticle($user, $data);

        $existingArticle = $this->articleRepository->findOneBy([
            'title' => $article->getTitle()
        ]);

        if ($existingArticle) {
            throw new ArticleException(__('Article with given Title already exists.'), 422);
        }

        $article = $this->articleRepository->save($article);

        return response()
            ->setData($article);
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
            ->setSlug($this->slug->slugify($data['title']))
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
