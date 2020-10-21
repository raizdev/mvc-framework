<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\Contract\UserOfTheWeekInterface;
use Ares\User\Repository\UserOfTheWeekRepository;
use Ares\User\Repository\UserRepository;

/**
 * Class UserOfTheWeek
 *
 * @package Ares\User\Entity
 */
class UserOfTheWeek extends DataObject implements UserOfTheWeekInterface
{
    /** @var string */
    public const TABLE = 'ares_uotw';

    /** @var array */
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(UserOfTheWeekInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return UserOfTheWeek
     */
    public function setId(int $id): UserOfTheWeek
    {
        return $this->setData(UserOfTheWeekInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(UserOfTheWeekInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $user_id
     *
     * @return UserOfTheWeek
     */
    public function setUserId(int $user_id): UserOfTheWeek
    {
        return $this->setData(UserOfTheWeekInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return User|null
     *
     * @throws DataObjectManagerException
     */
    public function getUser(): ?User
    {
        /** @var User $user */
        $user = $this->getData('user');

        if ($user) {
            return $user;
        }

        /** @var UserOfTheWeekRepository $userOfTheWeekRepository */
        $userOfTheWeekRepository = repository(UserOfTheWeekRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $userOfTheWeekRepository->getOneToOne(
            $userRepository,
            $this->getUserId(),
            'id'
        );

        if (!$user) {
            return null;
        }

        $this->setUser($user);

        return $user;
    }

    /**
     * @param User $user
     *
     * @return UserOfTheWeek
     */
    public function setUser(User $user): UserOfTheWeek
    {
        return $this->setData('user', $user);
    }
}
