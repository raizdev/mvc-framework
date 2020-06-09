<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Interfaces;

/**
 * Interface RepositoryInterface
 *
 * @package App\Interfaces
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