<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Room\Entity\Room;
use Doctrine\ORM\ORMException;

/**
 * Class RoomRepository
 *
 * @package Ares\Room\Repository
 */
class RoomRepository extends BaseRepository
{

    private const CACHE_PREFIX = 'ARES_ROOM_';

    /** @var string */
    protected string $entity = Room::class;

    /**
     * Get object by id.
     *
     * @param int $id
     * @return Room|null
     */
    public function get(int $id): ?object
    {
        $entity = $this->cacheService->get(self::CACHE_PREFIX . $id);

        if ($entity) {
            return json_decode($entity);
        }

        $entity = $this->find($id);
        $this->cacheService->set(self::CACHE_PREFIX . $id, $entity);

        return $entity;
    }

    public function getWithCache(int $id): ?array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('r')
            ->from(Room::class, 'r')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->setCacheable(true)
            ->getQuery()
            ->getArrayResult();
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
}
