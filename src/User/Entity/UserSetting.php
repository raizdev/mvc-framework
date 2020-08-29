<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity;

use Ares\Framework\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class UserOfTheWeek
 *
 * @package Ares\User\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="users_settings")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class UserSetting extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private ?User $user;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $achievement_score;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $can_change_name;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $block_following;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $block_friendrequests;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $block_roominvites;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $block_camera_follow;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $onlinetime;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $block_alerts;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $ignore_bots;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $ignore_pets;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param   int  $id
     *
     * @return UserSetting
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param   User|null  $user
     *
     * @return UserSetting
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getAchievementScore(): int
    {
        return $this->achievement_score;
    }

    /**
     * @param   int  $achievement_score
     *
     * @return UserSetting
     */
    public function setAchievementScore(int $achievement_score): self
    {
        $this->achievement_score = $achievement_score;

        return $this;
    }

    /**
     * @return int
     */
    public function getCanChangeName(): int
    {
        return $this->can_change_name;
    }

    /**
     * @param   int  $can_change_name
     *
     * @return UserSetting
     */
    public function setCanChangeName(int $can_change_name): self
    {
        $this->can_change_name = $can_change_name;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlockFollowing(): int
    {
        return $this->block_following;
    }

    /**
     * @param   int  $block_following
     *
     * @return UserSetting
     */
    public function setBlockFollowing(int $block_following): self
    {
        $this->block_following = $block_following;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlockFriendrequests(): int
    {
        return $this->block_friendrequests;
    }

    /**
     * @param   int  $block_friendrequests
     *
     * @return UserSetting
     */
    public function setBlockFriendrequests(int $block_friendrequests): self
    {
        $this->block_friendrequests = $block_friendrequests;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlockRoominvites(): int
    {
        return $this->block_roominvites;
    }

    /**
     * @param   int  $block_roominvites
     *
     * @return UserSetting
     */
    public function setBlockRoominvites(int $block_roominvites): self
    {
        $this->block_roominvites = $block_roominvites;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlockCameraFollow(): int
    {
        return $this->block_camera_follow;
    }

    /**
     * @param   int  $block_camera_follow
     *
     * @return UserSetting
     */
    public function setBlockCameraFollow(int $block_camera_follow): self
    {
        $this->block_camera_follow = $block_camera_follow;

        return $this;
    }

    /**
     * @return int
     */
    public function getOnlinetime(): int
    {
        return $this->onlinetime;
    }

    /**
     * @param   int  $onlinetime
     *
     * @return UserSetting
     */
    public function setOnlinetime(int $onlinetime): self
    {
        $this->onlinetime = $onlinetime;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlockAlerts(): int
    {
        return $this->block_alerts;
    }

    /**
     * @param   int  $block_alerts
     *
     * @return UserSetting
     */
    public function setBlockAlerts(int $block_alerts): self
    {
        $this->block_alerts = $block_alerts;

        return $this;
    }

    /**
     * @return int
     */
    public function getIgnoreBots(): int
    {
        return $this->ignore_bots;
    }

    /**
     * @param   int  $ignore_bots
     *
     * @return UserSetting
     */
    public function setIgnoreBots(int $ignore_bots): self
    {
        $this->ignore_bots = $ignore_bots;

        return $this;
    }

    /**
     * @return int
     */
    public function getIgnorePets(): int
    {
        return $this->ignore_pets;
    }

    /**
     * @param   int  $ignore_pets
     *
     * @return UserSetting
     */
    public function setIgnorePets(int $ignore_pets): self
    {
        $this->ignore_pets = $ignore_pets;

        return $this;
    }


    /**
     * Returns a copy of the current Entity safely
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'user' => $this->getUser(),
            'achievement_score' => $this->getAchievementScore(),
            'online_time' => $this->getOnlinetime(),
            'can_change_name' => $this->getCanChangeName(),
            'block_following' => $this->getBlockFollowing(),
            'block_friend_requests' => $this->getBlockFriendrequests(),
            'block_room_invites' => $this->getBlockRoominvites(),
            'block_camera_follow' => $this->getBlockCameraFollow(),
            'block_alerts' => $this->getBlockAlerts(),
            'ignore_bots' => $this->getIgnoreBots(),
            'ignore_pets' => $this->getIgnorePets()
        ];
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return serialize(get_object_vars($this));
    }

    /**
     * @param $data
     */
    public function unserialize($data): void
    {
        $values = unserialize($data);

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}