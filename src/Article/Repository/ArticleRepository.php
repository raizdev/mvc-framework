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
     * @param string $term
     * @param int    $page
     * @param int    $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function searchArticles(string $term, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->selectRaw('ares_articles.id, ares_articles.author_id, ares_articles.title, ares_articles.slug,
             ares_articles.image, ares_articles.likes, ares_articles.dislikes,
             count(ares_articles_comments.article_id) as comment_count')
            ->leftJoin(
                'ares_articles_comments',
                'ares_articles.id',
                '=',
                'ares_articles_comments.article_id'
            )->where('title', 'LIKE', '%'.$term.'%')
            ->where('hidden', 0)
            ->orderBy('comment_count', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @return Collection
     * @throws DataObjectManagerException
     */
    public function getPinnedArticles(): ?Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'pinned' => 1,
                'hidden' => 0
            ])
            ->selectRaw(
                'ares_articles.*, count(ares_articles_comments.article_id) as comment'
            )->leftJoin(
                'ares_articles_comments',
                'ares_articles.id',
                '=',
                'ares_articles_comments.article_id'
            )->orderBy('ares_articles.id', 'DESC')
            ->limit(3)
            ->addRelation('user');

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
            ->where('hidden', 0)
            ->selectRaw(
                'ares_articles.*,
                count(ares_articles_comments.article_id) as comments'
            )->leftJoin(
                'ares_articles_comments',
                'ares_articles.id',
                '=',
                'ares_articles_comments.article_id'
            )->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
