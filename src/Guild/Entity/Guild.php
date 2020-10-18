<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Guild\Entity\Contract\GuildInterface;

/**
 * Class Guild
 *
 * @package Ares\Guild\Entity
 */
class Guild extends DataObject implements GuildInterface
{
    /** @var string */
    public const TABLE = 'ares_guilds';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(GuildInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Guild
     */
    public function setId(int $id): Guild
    {
        return $this->setData(GuildInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(GuildInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $user_id
     *
     * @return Guild
     */
    public function setUserId(int $user_id): Guild
    {
        return $this->setData(GuildInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(GuildInterface::COLUMN_NAME);
    }

    /**
     * @param string $name
     *
     * @return Guild
     */
    public function setName(string $name): Guild
    {
        return $this->setData(GuildInterface::COLUMN_NAME, $name);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(GuildInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Guild
     */
    public function setDescription(string $description): Guild
    {
        return $this->setData(GuildInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return int
     */
    public function getRoomId(): int
    {
        return $this->getData(GuildInterface::COLUMN_ROOM_ID);
    }

    /**
     * @param int $room_id
     *
     * @return Guild
     */
    public function setRoomId(int $room_id): Guild
    {
        return $this->setData(GuildInterface::COLUMN_ROOM_ID, $room_id);
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->getData(GuildInterface::COLUMN_STATE);
    }

    /**
     * @param int $state
     *
     * @return Guild
     */
    public function setState(int $state): Guild
    {
        return $this->setData(GuildInterface::COLUMN_STATE, $state);
    }

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->getData(GuildInterface::COLUMN_BADGE);
    }

    /**
     * @param string $badge
     *
     * @return Guild
     */
    public function setBadge(string $badge): Guild
    {
        return $this->setData(GuildInterface::COLUMN_BADGE, $badge);
    }

    /**
     * @return int
     */
    public function getDateCreated(): int
    {
        return $this->getData(GuildInterface::COLUMN_DATE_CREATED);
    }

    /**
     * @param int $date_created
     *
     * @return Guild
     */
    public function setDateCreated(int $date_created): Guild
    {
        return $this->setData(GuildInterface::COLUMN_DATE_CREATED, $date_created);
    }
}
