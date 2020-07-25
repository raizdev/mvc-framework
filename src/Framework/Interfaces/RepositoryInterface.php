<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Interfaces;

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
    public function get(int $id): ?object;

    /**
     * Get array of objects by id.
     *
     * @return array
     */
    public function getList(): array;

    /**
     * Save object.
     *
     * @param object $model
     * @return object
     */
    public function save(object $model): object;

    /**
     * Delete object by id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

}
