<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Guild\Entity\Contract\GuildMemberInterface;

/**
 * Class GuildMember
 *
 * @package Ares\Guild\Entity
 */
class GuildMember extends DataObject implements GuildMemberInterface
{
    /** @var string */
    public const TABLE = 'guilds_members';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return GuildMember
     */
    public function setId(int $id): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getGuildId(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_GUILD_ID);
    }

    /**
     * @param int $guild_id
     *
     * @return GuildMember
     */
    public function setGuildId(int $guild_id): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_GUILD_ID, $guild_id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $user_id
     *
     * @return GuildMember
     */
    public function setUserId(int $user_id): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return int
     */
    public function getLevelId(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_LEVEL_ID);
    }

    /**
     * @param int $level_id
     *
     * @return GuildMember
     */
    public function setLevelId(int $level_id): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_LEVEL_ID, $level_id);
    }

    /**
     * @return int
     */
    public function getMemberSince(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_MEMBER_SINCE);
    }

    /**
     * @param int $member_since
     *
     * @return GuildMember
     */
    public function setMemberSince(int $member_since): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_MEMBER_SINCE, $member_since);
    }
}
