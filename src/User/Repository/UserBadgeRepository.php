<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserBadge;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;

/**
 * Class UserBadgeRepository
 *
 * @package Ares\User\Repository
 */
class UserBadgeRepository extends BaseRepository
{
    /** @var string */
    private const CACHE_PREFIX = 'ARES_USER_BADGE_';

    /** @var string */
    private const CACHE_COLLECTION_PREFIX = 'ARES_USER_BADGE_COLLECTION_';

    /** @var string */
    protected string $entity = UserBadge::class;

    /**
     * Get object by id.
     *
     * @param   int   $id
     *
     * @param   bool  $cachedEntity
     *
     * @return UserBadge|null
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
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
     * @param $user
     *
     * @return int|mixed|string
     */
    public function getSlotBadges($user): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('b')
            ->from('Ares\User\Entity\UserBadge', 'b')
            ->andWhere('b.user = ?1')
            ->andWhere('b.slot_id > 1')
            ->orderBy('b.slot_id', 'ASC')
            ->setParameter(1, $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param      $criteria
     * @param null $orderBy
     *
     * @return UserBadge|null
     */
    public function getBy($criteria, $orderBy = null): ?object
    {
        return $this->findOneBy($criteria, $orderBy);
    }

    /**
     * @param object $model
     *
     * @return object
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException|\Psr\Cache\InvalidArgumentException
     */
    public function save(object $model): object
    {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush();

        $this->cacheService->set(self::CACHE_COLLECTION_PREFIX . $model->getId(), serialize($model));

        return $model;
    }

    /**
     * @param  object  $model
     *
     * @return UserBadge
     * @throws ORMException
     * @throws PhpfastcacheSimpleCacheException
     * @throws OptimisticLockException|\Psr\Cache\InvalidArgumentException
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
     * @return array|object[]
     * @throws PhpfastcacheSimpleCacheException|\Psr\Cache\InvalidArgumentException
     */
    public function getList(SearchCriteriaInterface $searchCriteria, bool $cachedEntity = true)
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
     * @throws PhpfastcacheSimpleCacheException
     * @throws OptimisticLockException|\Psr\Cache\InvalidArgumentException
     */
    public function delete(int $id): bool
    {
        $model = $this->get($id);

        if (!$model) {
            return false;
        }

        $this->getEntityManager()->remove($model);
        $this->getEntityManager()->flush();

        $this->cacheService->delete(self::CACHE_PREFIX . $id);

        return true;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @param bool                    $cachedEntity
     *
     * @return PaginatedArrayCollection
     * @throws PhpfastcacheSimpleCacheException|\Psr\Cache\InvalidArgumentException
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