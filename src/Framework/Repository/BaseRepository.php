<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Repository;

use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Service\CacheService;
use Ares\Payment\Entity\Payment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Jhg\DoctrinePagination\ORM\PaginatedRepository;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class BaseRepository
 *
 * @package Ares\Framework\Repository
 */
abstract class BaseRepository extends PaginatedRepository
{
    /** @var string */
    protected const CACHE_PREFIX = '';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = '';

    /**
     * @var string
     */
    protected string $entity;

    /**
     * @var CacheService
     */
    protected CacheService $cacheService;

    /**
     * BaseRepository constructor.
     *
     * @param   EntityManager  $em
     * @param   CacheService   $cacheService
     */
    public function __construct(
        EntityManager $em,
        CacheService $cacheService
    ) {
        parent::__construct($em, new ClassMetadata($this->entity));
        $this->cacheService = $cacheService;
    }

    /**
     * Get object by id.
     *
     * @param   int   $id
     *
     * @param   bool  $cachedEntity
     *
     * @return Object|null
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function get(int $id, bool $cachedEntity = true): ?object
    {
        $entity = $this->cacheService->get(self::CACHE_PREFIX . $id);

        if ($entity && $cachedEntity) {
            return unserialize($entity);
        }

        $entity = $this->find($id);

        $this->cacheService->set(self::CACHE_PREFIX . $id, serialize($entity));

        return $entity;
    }

    /**
     * @param   object  $model
     *
     * @return object
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function save(object $model): object
    {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush();

        $this->cacheService->set(self::CACHE_COLLECTION_PREFIX . $model->getId(), serialize($model));

        return $model;
    }

    /**
     * @param      $criteria
     * @param null $orderBy
     *
     * @return Object|null
     */
    public function getOneBy($criteria, $orderBy = null): ?object
    {
        return $this->findOneBy($criteria, $orderBy);
    }

    /**
     * @param   object  $model
     *
     * @return Object
     * @throws ORMException
     * @throws PhpfastcacheSimpleCacheException
     * @throws OptimisticLockException
     * @throws InvalidArgumentException
     */
    public function update(object $model): object
    {
        $this->getEntityManager()->flush();

        $this->cacheService->set(self::CACHE_PREFIX . $model->getId(), serialize($model));

        return $model;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @param bool                    $cachedEntity
     *
     * @return ArrayCollection
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getList(SearchCriteriaInterface $searchCriteria, bool $cachedEntity = true): ArrayCollection
    {
        $cacheKey = $searchCriteria->getCacheKey();

        $collection = $this->cacheService->get(self::CACHE_COLLECTION_PREFIX . $cacheKey);

        if ($collection && $cachedEntity) {
            return unserialize($collection);
        }

        $collection = $this->findBy(
            $searchCriteria->getFilters(),
            $searchCriteria->getOrders(),
            $searchCriteria->getLimit(),
            $searchCriteria->getOffset()
        );

        $this->cacheService->set(self::CACHE_COLLECTION_PREFIX . $cacheKey, serialize($collection));

        return $collection;
    }

    /**
     * Delete object by id.
     *
     * @param   int  $id
     *
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function delete(int $id): bool
    {
        $model = $this->get($id, false);

        if (!$model) {
            return false;
        }

        $this->getEntityManager()->remove($model);
        $this->getEntityManager()->flush();

        $this->cacheService->delete(self::CACHE_PREFIX . $id);

        return true;
    }

    /**
     * @param   SearchCriteriaInterface  $searchCriteria
     *
     * @param   bool                     $cachedEntity
     *
     * @return PaginatedArrayCollection
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function paginate(SearchCriteriaInterface $searchCriteria, bool $cachedEntity = true): PaginatedArrayCollection
    {
        $cacheKey = $searchCriteria->getCacheKey();

        $collection = $this->cacheService->get(self::CACHE_COLLECTION_PREFIX . $cacheKey);

        if ($collection && $cachedEntity) {
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
