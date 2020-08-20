<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Repository;

use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\Messenger\Entity\MessengerFriendship;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class MessengerRepository
 *
 * @package Ares\Messenger\Repository
 */
class MessengerRepository extends BaseRepository
{
    const CACHE_COLLECTION_PREFIX = 'ARES_MESSENGER_COLLECTION';

    /** @var string */
    protected string $entity = MessengerFriendship::class;

    /**
     * Get object by id.
     *
     * @param int $id
     *
     * @return MessengerFriendship|null
     */
    public function get(int $id): ?object
    {
        return $this->find($id);
    }

    /**
     * @param object $model
     *
     * @return MessengerFriendship
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
