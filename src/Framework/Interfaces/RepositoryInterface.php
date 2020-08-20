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
     * @param   SearchCriteriaInterface  $searchCriteria
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

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
