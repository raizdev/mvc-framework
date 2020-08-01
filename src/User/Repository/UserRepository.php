<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\User\Entity\User;
use Ares\Framework\Repository\BaseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class UserRepository
 *
 * @package Ares\User\Repository
 */
class UserRepository extends BaseRepository
{
    /**
     * @var EntityRepository|ObjectRepository
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
     * @param $username
     *
     * @return User|object
     */
    public function getByUsername(string $username)
    {
        return $this->repository->findOneBy([
            'username' => $username
        ]);
    }

    /**
     * @param $mail
     *
     * @return User|object
     */
    public function getByMail(string $mail)
    {
        return $this->repository->findOneBy([
            'mail' => $mail
        ]);
    }

    /**
     * @param array $data
     *
     * @return object|null
     */
    public function getBy(array $data): ?object
    {
        return $this->repository->findOneBy($data);
    }

    /**
     * Get object by id.
     *
     * @param int $id
     * @return User|null
     */
    public function get(int $id): ?object
    {
        return $this->repository->find($id);
    }

    /**
     * @param      $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     *
     * @return array|object[]
     */
    public function getList($criteria, $orderBy = null, $limit = null, $offset = null): array
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     *
     * @return int
     */
    public function count(array $criteria): int
    {
        return $this->repository->count($criteria);
    }

    /**
     * @param object $model
     *
     * @return User
     * @throws ORMException
     */
    public function save(object $model): object
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $model;
    }

    /**
     * Delete object by id.
     *
     * @param int $id
     * @return bool
     * @throws ORMException
     */
    public function delete(int $id): bool
    {
        $model = $this->get($id);

        if (!$model) {
            return false;
        }

        $this->entityManager->remove($model);
        $this->entityManager->flush();

        return true;
    }
}
