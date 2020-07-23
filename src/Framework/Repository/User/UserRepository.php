<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Repository\User;

use Ares\Framework\Entity\User;
use Ares\Framework\Repository\BaseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

/**
 * Class UserRepository
 *
 * @package Ares\Framework\Repository\User
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
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $data
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
            ->setPassword(password_hash($data['password'], PASSWORD_ARGON2ID))
            ->setTicket($data['auth_ticket'])
            ->setLook($data['look'])
            ->setMotto($data['motto'])
            ->setCredits($data['credits'])
            ->setPoints($data['points'])
            ->setPixels($data['pixels'])
            ->setIPRegister($data['ip_register'])
            ->setCurrentIP($data['ip_current'])
            ->setAccountCreated($data['account_created']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function createTicket(array $data): ?string
    {

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
     * @param array $data
     *
     * @return mixed|void
     */
    public function update(array $data)
    {
    }

    /**
     * @param int $id
     *
     * @return object
     */
    public function one(int $id): object
    {
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
    }
}
