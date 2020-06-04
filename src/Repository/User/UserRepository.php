<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */
namespace App\Repository\User;

use App\Entity\User;
use App\Repository\BaseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class UserRepository extends BaseRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * UserRepository constructor.
     *
     * @param   EntityManager  $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        parent::__construct($entityManager);
        $this->repository = $entityManager->getRepository(User::class);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->repository->findAll();
    }
}