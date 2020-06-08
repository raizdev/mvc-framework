<?php declare(strict_types=1);
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
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class UserRepository
 */
class UserRepository extends BaseRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * UserRepository constructor.
     *
     * @param   EntityManager  $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository    = $entityManager->getRepository(User::class);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param   array  $data
     */
    public function save(array $data)
    {
    }

    /**
     * @param   array  $data
     *
     * @return User
     * @throws ORMException
     */
    public function create(array $data): User
    {
        $user = new User();
        $user->setUsername($data['username']);
        $user->setMail($data['mail']);
        $user->setPassword(password_hash(
                $data['password'],
                PASSWORD_ARGON2ID)
        );
        /* @TODO Make a secure ticket... */
        $user->setTicket('21312312312');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}