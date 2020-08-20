<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\User\Entity\User;
use Ares\Framework\Repository\BaseRepository;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class UserRepository
 *
 * @package Ares\User\Repository
 */
class UserRepository extends BaseRepository
{

    private const CACHE_PREFIX = 'ARES_USER_';

    private const CACHE_COLLECTION_PREFIX = 'ARES_USER_COLLECTION_';

    /** @var string */
    protected string $entity = User::class;

    /**
     * @param $username
     *
     * @return User|object
     */
    public function getByUsername(string $username)
    {
        return $this->findOneBy([
            'username' => $username
        ]);
    }

    /**
     * @param $mail
     *
     * @return User|object
     */
    public function getByMail(string $mail)
    {
        return $this->findOneBy([
            'mail' => $mail
        ]);
    }

    /**
     * @param array $data
     *
     * @return object|null
     */
    public function getBy(array $data): ?object
    {
        return $this->findOneBy($data);
    }

    /**
     * Get object by id.
     *
     * @param int $id
     *
     * @return User|null
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function get(int $id): ?object
    {
        $entity = $this->cacheService->get(self::CACHE_PREFIX . $id);

        if ($entity) {
            return $entity;
        }

        $entity = $this->find($id);
        $this->cacheService->set(self::CACHE_PREFIX . $id, $entity);

        return $entity;
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
     * @param object $model
     *
     * @return User
     * @throws ORMException
     */
    public function save(object $model): object
    {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush();

        return $model;
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
