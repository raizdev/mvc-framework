<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Interfaces;

use Doctrine\ORM\AbstractQuery;

/**
 * Interface RepositoryInterface
 *
 * @package Ares\Framework\Interfaces
 */
interface RepositoryInterface
{
    /**
     * Get object by id.
     *
     * @param int $id
     * @return object|null
     */
    public function get(int $id);

    /**
     * Get array of objects by id.
     *
     * @param array $criteria
     * @param null  $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     */
    public function getList(array $criteria, $orderBy = null, $limit = null, $offset = null);

    /**
     * Save object.
     *
     * @param object $model
     * @return object
     */
    public function save(object $model): object;

    /**
     * @param   int         $page
     * @param   int         $rpp
     * @param   array       $criteria
     * @param   array|null  $orderBy
     * @param   int         $hydrateMode
     *
     * @return mixed
     */
    public function paginate(int $page, int $rpp, array $criteria = [], array $orderBy = null, $hydrateMode = AbstractQuery::HYDRATE_OBJECT);

    /**
     * Delete object by id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
