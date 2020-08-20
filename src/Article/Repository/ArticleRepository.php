<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Repository;

use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\Article\Entity\Article;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class ArticleRepository
 *
 * @package Ares\Article\Repository
 */
class ArticleRepository extends BaseRepository
{
    private const CACHE_PREFIX = 'ARES_ARTICLE_';
    private const CACHE_COLLECTION_PREFIX = 'ARES_ARTICLE_COLLECTION_';

    /** @var string */
    protected string $entity = Article::class;

    /**
     * Get object by id.
     *
     * @param int $id
     *
     * @return Article|null
     */
    public function get(int $id): ?object
    {
        return $this->find($id);
    }

    /**
     * @param object $model
     *
     * @return Article
     * @throws ORMException
     */
    public function save(object $model): object
    {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush();

        return $model;
    }

    /**
     * @param      $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return array|object[]
     */
    public function getList($criteria, $orderBy = null, $limit = null, $offset = null)
    {
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Delete object by id.
     *
     * @param int $id
     * @return bool
     * @throws ORMException
     */
    public function delete(int $id): bool
    {
        $model = $this->get($id);

        if (!$model) {
            return false;
        }

        $this->getEntityManager()->remove($model);
        $this->getEntityManager()->flush();

        return true;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return PaginatedArrayCollection
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function paginate(SearchCriteriaInterface $searchCriteria): PaginatedArrayCollection
    {
        $cacheKey = $searchCriteria->encodeCriteria();

        $entity = $this->cacheService->get(self::CACHE_COLLECTION_PREFIX . $cacheKey);

        if ($entity) {
            return unserialize($entity);
        }

        $entity = $this->findPageBy(
            $searchCriteria->getPage(),
            $searchCriteria->getLimit(),
            $searchCriteria->getFilters(),
            $searchCriteria->getOrders()
        );

        $this->cacheService->set(self::CACHE_COLLECTION_PREFIX . $cacheKey, serialize($entity));

        return $entity;
    }
}
