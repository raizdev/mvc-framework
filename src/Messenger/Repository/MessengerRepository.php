<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Messenger\Entity\MessengerFriendship;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class MessengerRepository
 *
 * @package Ares\Messenger\Repository
 */
class MessengerRepository extends BaseRepository
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
     * NewsRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(MessengerFriendship::class);
    }

    /**
     * Get object by id.
     *
     * @param int $id
     * @return MessengerFriendship|null
     */
    public function get(int $id): ?object
    {
        return $this->repository->find($id);
    }

    /**
     * @param object $model
     *
     * @return MessengerFriendship
     * @throws ORMException
     */
    public function save(object $model): object
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();

        return $model;
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
