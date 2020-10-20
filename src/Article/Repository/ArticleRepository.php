<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Article\Entity\Article;
use Ares\Article\Entity\Comment;

/**
 * Class ArticleRepository
 *
 * @package Ares\Article\Repository
 */
class ArticleRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ARTICLE_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ARTICLE_COLLECTION_';

    /** @var string */
    protected string $entity = Article::class;

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
