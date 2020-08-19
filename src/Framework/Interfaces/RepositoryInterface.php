<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Interfaces;

use Ares\Framework\Model\SearchCriteria;

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
     * @param SearchCriteria $searchCriteria
     *
     * @return mixed
     */
    public function paginate(SearchCriteria $searchCriteria);

    /**
     * Delete object by id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
