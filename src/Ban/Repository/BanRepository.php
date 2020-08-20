<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Ban\Repository;

use Ares\Ban\Entity\Ban;
use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Repository\BaseRepository;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;

/**
 * Class BanRepository
 *
 * @package Ares\Ban\Repository
 */
class BanRepository extends BaseRepository
{
    private const CACHE_COLLECTION_PREFIX = 'ARES_BAN_COLLECTION';

    /** @var string */
    protected string $entity = Ban::class;

    /**
     * Get object by id.
     *
     * @param int $id
     * @return Ban|null
     */
    public function get(int $id): ?object
    {
        return $this->find($id);
    }

    /**
     * @param      $criteria
     * @param null $orderBy
     *
     * @return Ban|null
     */
    public function getBy($criteria, $orderBy = null): ?object
    {
        return $this->findOneBy($criteria, $orderBy);
    }

    /**
     * @param object $model
     *
     * @return Ban
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
     * @param   SearchCriteriaInterface  $searchCriteria
     *
     * @return PaginatedArrayCollection
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function paginate(SearchCriteriaInterface $searchCriteria): PaginatedArrayCollection
    {
        $cacheKey = $searchCriteria->getCacheKey();

        $collection = $this->cacheService->get(self::CACHE_COLLECTION_PREFIX . $cacheKey);

        if ($collection) {
            return unserialize($collection);
        }

        $collection = $this->findPageBy(
            $searchCriteria->getPage(),
            $searchCriteria->getLimit(),
            $searchCriteria->getFilters(),
            $searchCriteria->getOrders()
        );

        $this->cacheService->set(self::CACHE_COLLECTION_PREFIX . $cacheKey, serialize($collection));

        return $collection;
    }
}
