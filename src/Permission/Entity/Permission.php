<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Permission\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Permission\Entity\Contract\PermissionInterface;

/**
 * Class Permission
 *
 * @package Ares\Permission\Entity
 */
class Permission extends DataObject implements PermissionInterface
{
    /** @var string */
    public const TABLE = 'permissions';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(PermissionInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Permission
     */
    public function setId(int $id): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getRankName(): string
    {
        return $this->getData(PermissionInterface::COLUMN_RANK_NAME);
    }

    /**
     * @param string $rank_name
     *
     * @return Permission
     */
    public function setRankName(string $rank_name): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_RANK_NAME, $rank_name);
    }

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->getData(PermissionInterface::COLUMN_BADGE);
    }

    /**
     * @param string $badge
     *
     * @return Permission
     */
    public function setBadge(string $badge): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_BADGE, $badge);
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->getData(PermissionInterface::COLUMN_LEVEL);
    }

    /**
     * @param int $level
     *
     * @return Permission
     */
    public function setLevel(int $level): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_LEVEL, $level);
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->getData(PermissionInterface::COLUMN_PREFIX);
    }

    /**
     * @param string $prefix
     *
     * @return Permission
     */
    public function setPrefix(string $prefix): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_PREFIX, $prefix);
    }

    /**
     * @return string
     */
    public function getPrefixColor(): string
    {
        return $this->getData(PermissionInterface::COLUMN_PREFIX_COLOR);
    }

    /**
     * @param string $prefix_color
     *
     * @return Permission
     */
    public function setPrefixColor(string $prefix_color): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_PREFIX_COLOR, $prefix_color);
    }
}
