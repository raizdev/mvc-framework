<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Repository;

use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Model\SearchCriteria;
use Ares\Framework\Repository\BaseRepository;
use Ares\Framework\Service\CacheService;
use Ares\Room\Entity\Room;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class RoomRepository
 *
 * @package Ares\Room\Repository
 */
class RoomRepository extends BaseRepository
{
    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    public function __construct(EntityManager $em, CacheService $cacheService, DoctrineSearchCriteria $searchCriteria)
    {
        parent::__construct($em, $cacheService);
        $this->searchCriteria = $searchCriteria;
    }

    /** @var string */
    private const CACHE_PREFIX = 'ARES_ROOM_';

    /** @var string */
    private const CACHE_COLLECTION_PREFIX = 'ARES_ROOM_COLLECTION_';

    /** @var string */
    protected string $entity = Room::class;

    /**
     * Get object by id.
     *
     * @param int $id
     *
     * @return mixed|object|null
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function get(int $id)
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
     * @param object $model
     *
     * @return Room
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
     *
     * @return bool
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
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
