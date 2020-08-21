<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\GuildMember;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class GuildMemberRepository
 *
 * @package Ares\Guild\Repository
 */
class GuildMemberRepository extends BaseRepository
{
    /** @var string */
    private const CACHE_PREFIX = 'ARES_GUILD_MEMBER_';

    /** @var string */
    private const CACHE_COLLECTION_PREFIX = 'ARES_GUILD_MEMBER_COLLECTION_';

    /** @var string */
    protected string $entity = GuildMember::class;

    /**
     * Get object by id.
     *
     * @param   int  $id
     *
     * @return GuildMember|null
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function get(int $id): ?object
    {
        $entity = $this->cacheService->get(self::CACHE_PREFIX . $id);

        if ($entity) {
            return unserialize($entity);
        }

        $entity = $this->find($id);

        $this->cacheService->set(self::CACHE_PREFIX . $id, serialize($entity));

        return $entity;
    }

    /**
     * @param   object  $model
     *
     * @return GuildMember
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function save(object $model): object
    {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush();

        $this->cacheService->set(self::CACHE_PREFIX . $model->getId(), serialize($model));

        return $model;
    }

    /**
     * @param   SearchCriteriaInterface  $searchCriteria
     *
     * @return array|object[]
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $cacheKey = $searchCriteria->getCacheKey();

        $collection = $this->cacheService->get(self::CACHE_COLLECTION_PREFIX . $cacheKey);

        if ($collection) {
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
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws PhpfastcacheSimpleCacheException
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

        $this->cacheService->delete(self::CACHE_PREFIX . $id);

        return true;
    }

    /**
     * @param   SearchCriteriaInterface  $searchCriteria
     *
     * @return PaginatedArrayCollection
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
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
