<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Repository;

use Ares\Article\Entity\Comment;
use Ares\Framework\Model\SearchCriteria;
use Ares\Framework\Repository\BaseRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CommentRepository
 *
 * @package Ares\Article\Repository
 */
class CommentRepository extends BaseRepository
{
    /** @var string */
    private const CACHE_PREFIX = 'ARES_COMMENT_';

    /** @var string */
    private const CACHE_COLLECTION_PREFIX = 'ARES_COMMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Comment::class;

    /**
     * @param int $id
     * @param bool $cachedEntity
     * @return object|void|null|Comment
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function get(int $id, bool $cachedEntity = true)
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
     * @param SearchCriteria $searchCriteria
     * @param bool $cachedEntity
     * @return mixed|void
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getList(SearchCriteria $searchCriteria, bool $cachedEntity = true)
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
     * @param object $model
     * @return object
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(object $model): object
    {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush();

        $this->cacheService->set(self::CACHE_COLLECTION_PREFIX . $model->getId(), serialize($model));

        return $model;
    }

    /**
     * @param object $model
     * @return object
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(object $model): object
    {
        $this->getEntityManager()->flush();

        $this->cacheService->set(self::CACHE_PREFIX . $model->getId(), serialize($model));

        return $model;
    }

    /**
     * @param SearchCriteria $searchCriteria
     * @param bool $cachedEntity
     * @return mixed|void
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function paginate(SearchCriteria $searchCriteria, bool $cachedEntity = true)
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

    /**
     * @param int $id
     * @return bool
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ORMException
     * @throws OptimisticLockException
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
}