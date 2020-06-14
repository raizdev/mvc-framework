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
use Doctrine\ORM\ORMException;

/**
 * Class UserRepository
 *
 * @package App\Repository\User
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
     *
     * @return User
     * @throws ORMException
     */
    public function create(array $data): User
    {
        $user = new User();
        $user
            ->setUsername($data['username'])
            ->setMail($data['mail'])
            ->setPassword(password_hash(
                    $data['password'],
                    PASSWORD_ARGON2ID)
            )
            /* @TODO Make a secure ticket... */
            ->setTicket('21312312312');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param $data
     *
     * @return User|object
     */
    public function findByUsername($data)
    {
        return $this->repository->findOneBy([
            'username' => $data
        ]);
    }

    /**
     * @param $id
     *
     * @return object|null
     */
    public function find($id): ?object
    {
        return $this->repository->find($id);
    }

    /**
     * @param   array  $data
     *
     * @return mixed|void
     */
    public function update(array $data)
    {
    }

    /**
     * @param   int  $id
     *
     * @return object
     */
    public function one(int $id): object
    {
    }

    /**
     * @param   int  $id
     */
    public function delete(int $id)
    {
    }
}