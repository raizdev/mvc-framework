<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\News\Repository;

use Ares\Framework\Interfaces\RepositoryInterface;
use Ares\News\Entity\News;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMException;

/**
 * Class NewsRepository
 *
 * @package Ares\News\Repository
 */
class NewsRepository implements RepositoryInterface
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
     * NewsRepository constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(News::class);
    }

    /**
     * Get object by id.
     *
     * @param int $id
     * @return News|null
     */
    public function get(int $id): ?object
    {
        return $this->repository->find($id);
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param object $model
     *
     * @return News
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
