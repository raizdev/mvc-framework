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
     * @return array
     */
    public function all(): array;

    /**
     * @param   int  $id
     *
     * @return object
     */
    public function one(int $id): object;

    /**
     * @param   array  $data
     *
     * @return mixed
     */
    public function update(array $data);

    /**
     * @param   array  $data
     *
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param   int  $id
     *
     * @return mixed
     */
    public function delete(int $id);
}
