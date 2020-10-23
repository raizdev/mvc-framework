<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Article\Entity\Article;
use Ares\Article\Entity\Comment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ares\Framework\Model\Query\Collection;

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
                Join::WITH,
                'a.id = c.article'
            )
            ->where('a.title LIKE :term')
            ->orderBy('comments', 'DESC')
            ->groupBy('a.id')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Collection
     * @throws DataObjectManagerException
     */
    public function getPinnedArticles(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'pinned' => 1,
                'hidden' => 0
            ])
            ->addRelation('user')
            ->orderBy('id', 'DESC')
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedArticleList(int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('user')
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
