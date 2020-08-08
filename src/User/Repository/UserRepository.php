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
    /** @var string */
    protected string $entity = User::class;

    /**
     * @param $username
     *
     * @return User|object
     */
    public function getByUsername(string $username)
    {
        return $this->findOneBy([
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
        return $this->findOneBy([
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
        return $this->findOneBy($data);
    }

    /**
     * Get object by id.
     *
     * @param int $id
     * @return User|null
     */
    public function get(int $id): ?object
    {
        return $this->find($id);
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
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     *
     * @return int
     */
    public function count(array $criteria): int
    {
        return $this->count($criteria);
    }

    /**
     * @param object $model
     *
     * @return User
     * @throws ORMException
     */
    public function save(object $model): object
    {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush();

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

        $this->getEntityManager()->remove($model);
        $this->getEntityManager()->flush();

        return true;
    }
}
