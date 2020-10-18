<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Room\Entity\Contract\RoomInterface;

/**
 * Class Room
 *
 * @package Ares\Room\Entity
 */
class Room extends DataObject implements RoomInterface
{
    /** @var string */
    public const TABLE = 'rooms';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(RoomInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Room
     */
    public function setId(int $id): Room
    {
        return $this->setData(RoomInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->getData(RoomInterface::COLUMN_OWNER_ID);
    }

    /**
     * @param int $owner_id
     *
     * @return Room
     */
    public function setOwnerId(int $owner_id): Room
    {
        return $this->setData(RoomInterface::COLUMN_OWNER_ID, $owner_id);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(RoomInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Room
     */
    public function setDescription(string $description): Room
    {
        return $this->setData(RoomInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->getData(RoomInterface::COLUMN_STATE);
    }

    /**
     * @param string $state
     *
     * @return Room
     */
    public function setState(string $state): Room
    {
        return $this->setData(RoomInterface::COLUMN_STATE, $state);
    }

    /**
     * @return int
     */
    public function getUsers(): int
    {
        return $this->getData(RoomInterface::COLUMN_USERS);
    }

    /**
     * @param int $users
     *
     * @return Room
     */
    public function setUsers(int $users): Room
    {
        return $this->setData(RoomInterface::COLUMN_USERS, $users);
    }

    /**
     * @return int
     */
    public function getUsersMax(): int
    {
        return $this->getData(RoomInterface::COLUMN_USERS_MAX);
    }

    /**
     * @param int $users_max
     *
     * @return Room
     */
    public function setUsersMax(int $users_max): Room
    {
        return $this->setData(RoomInterface::COLUMN_USERS_MAX, $users_max);
    }

    /**
     * @return int
     */
    public function getGuildId(): int
    {
        return $this->getData(RoomInterface::COLUMN_GUILD_ID);
    }

    /**
     * @param int $guild_id
     *
     * @return Room
     */
    public function setGuildId(int $guild_id): Room
    {
        return $this->setData(RoomInterface::COLUMN_GUILD_ID, $guild_id);
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->getData(RoomInterface::COLUMN_SCORE);
    }

    /**
     * @param int $score
     *
     * @return Room
     */
    public function setScore(int $score): Room
    {
        return $this->setData(RoomInterface::COLUMN_SCORE, $score);
    }
}
