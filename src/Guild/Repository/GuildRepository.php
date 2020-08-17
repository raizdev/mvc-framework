<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\Guild;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\ORMException;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;

/**
 * Class GuildRepository
 *
 * @package Ares\Guild\Repository
 */
class GuildRepository extends BaseRepository
{
    /** @var string */
    protected string $entity = Guild::class;

    /**
     * Get object by id.
     *
     * @param int $id
     * @return Guild|null
     */
    public function get(int $id): ?object
    {
        return $this->find($id);
    }

    /**
     * @param object $model
     *
     * @return Guild
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
     * @param int        $page
     * @param int        $rpp
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int        $hydrateMode
     *
     * @return PaginatedArrayCollection
     */
    public function paginate(int $page, int $rpp, array $criteria = [], array $orderBy = null, $hydrateMode = AbstractQuery::HYDRATE_OBJECT): PaginatedArrayCollection
    {
        return $this->findPageBy($page, $rpp, $criteria, $orderBy, $hydrateMode);
    }
}
