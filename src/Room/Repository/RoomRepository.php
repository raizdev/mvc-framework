<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Room\Entity\Room;
use Doctrine\ORM\AbstractQuery;
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
    /** @var string */
    private const CACHE_PREFIX = 'ARES_ROOM_';

    /** @var PaginatedArrayCollection */
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
     * @param int        $page
     * @param int        $rpp
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int        $hydrateMode
     *
     * @return PaginatedArrayCollection
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function paginate(int $page, int $rpp, array $criteria = [], array $orderBy = null, $hydrateMode = AbstractQuery::HYDRATE_OBJECT): PaginatedArrayCollection
    {
        $entity = $this->cacheService->get(self::CACHE_COLLECTION_PREFIX . $page);

        if ($entity) {
            return unserialize($entity);
        }

        $entity = $this->findPageBy($page, $rpp, $criteria, $orderBy, $hydrateMode);

        $this->cacheService->set(self::CACHE_COLLECTION_PREFIX . $page, serialize($entity));

        return $entity;
    }
}
