<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity;

use Ares\Framework\Model\DataObject;
use Ares\User\Entity\Contract\UserOfTheWeekInterface;

/**
 * Class UserOfTheWeek
 *
 * @package Ares\User\Entity
 */
class UserOfTheWeek extends DataObject implements UserOfTheWeekInterface
{
    /** @var string */
    public const TABLE = 'ares_uotw';

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
}
