<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Article\Entity\Article;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Ares\Article\Entity\Comment;

/**
 * Class ArticleRepository
 *
 * @package Ares\Article\Repository
 */
class ArticleRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_ARTICLE_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_ARTICLE_COLLECTION_';

    /** @var string */
    protected string $entity = Article::class;

    /**
     * @param string $slug
     * @param bool   $cachedEntity
     *
     * @return mixed|object|null
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function findBySlug(string $slug, bool $cachedEntity = true)
    {
        $entity = $this->cacheService->get(self::CACHE_PREFIX . $slug);

        if ($entity && $cachedEntity) {
            return unserialize($entity);
        }

        $entity = $this->getOneBy([
            'slug' => $slug
        ]);

        $this->cacheService->set(self::CACHE_PREFIX . $slug, serialize($entity));

        return $entity;
    }

    /**
     * Searchs articles by search term.
     *
     * @param string $term
     * @return int|mixed|string
     */
    public function searchArticles(string $term): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('a.id, a.title, a.description, count(c.article) as comments')
            ->from(Article::class, 'a')
            ->leftJoin(
                Comment::class,
                'c',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'a.id = c.article'
            )
            ->where('a.title LIKE :term')
            ->orderBy('comments', 'DESC')
            ->groupBy('a.id')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult();
    }
}
