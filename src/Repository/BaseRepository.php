<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Repository;

use Doctrine\ORM\EntityManager;

/**
 * Class BaseRepository
 */
abstract class BaseRepository
{
    /**
     * @var EntityManager
     */
    private ?EntityManager $entityManager;

    /**
     * BaseRepository constructor.
     *
     * @param   EntityManager  $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->setEntityManager($entityManager);
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param   EntityManager  $entityManager
     */
    private function setEntityManager($entityManager): void
    {
        $this->entityManager = $entityManager;
    }
}
