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
     * @ORM\Column(type="integer")
     */
    private string $achievement_score;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0',1')")
     */
    private string $can_change_name;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0',1')")
     */
    private string $block_following;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0',1')")
     */
    private string $block_friendrequests;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0',1')")
     */
    private string $block_roominvites;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0',1')")
     */
    private string $block_camera_follow;

    /**
     * @ORM\Column(type="integer")
     */
    private string $online_time;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0',1')")
     */
    private string $block_alerts;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0',1')")
     */
    private string $ignore_bots;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('0',1')")
     */
    private string $ignore_pets;

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
     * @return string
     */
    public function getAchievementScore(): string
    {
        return $this->achievement_score;
    }

    /**
     * @param string $achievement_score
     *
     * @return UserSetting
     */
    public function setAchievementScore(string $achievement_score): self
    {
        $this->achievement_score = $achievement_score;

        return $this;
    }

    /**
     * @return string
     */
    public function getCanChangeName(): string
    {
        return $this->can_change_name;
    }

    /**
     * @param string $can_change_name
     *
     * @return UserSetting
     */
    public function setCanChangeName(string $can_change_name): self
    {
        $this->can_change_name = $can_change_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockFollowing(): string
    {
        return $this->block_following;
    }

    /**
     * @param string $block_following
     *
     * @return UserSetting
     */
    public function setBlockFollowing(string $block_following): self
    {
        $this->block_following = $block_following;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockFriendrequests(): string
    {
        return $this->block_friendrequests;
    }

    /**
     * @param string $block_friendrequests
     *
     * @return UserSetting
     */
    public function setBlockFriendrequests(string $block_friendrequests): self
    {
        $this->block_friendrequests = $block_friendrequests;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockRoominvites(): string
    {
        return $this->block_roominvites;
    }

    /**
     * @param string $block_roominvites
     *
     * @return UserSetting
     */
    public function setBlockRoominvites(string $block_roominvites): self
    {
        $this->block_roominvites = $block_roominvites;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockCameraFollow(): string
    {
        return $this->block_camera_follow;
    }

    /**
     * @param string $block_camera_follow
     *
     * @return UserSetting
     */
    public function setBlockCameraFollow(string $block_camera_follow): self
    {
        $this->block_camera_follow = $block_camera_follow;

        return $this;
    }

    /**
     * @return string
     */
    public function getOnlinetime(): string
    {
        return $this->online_time;
    }

    /**
     * @param string $online_time
     *
     * @return UserSetting
     */
    public function setOnlinetime(string $online_time): self
    {
        $this->online_time = $online_time;

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockAlerts(): string
    {
        return $this->block_alerts;
    }

    /**
     * @param string $block_alerts
     *
     * @return UserSetting
     */
    public function setBlockAlerts(string $block_alerts): self
    {
        $this->block_alerts = $block_alerts;

        return $this;
    }

    /**
     * @return string
     */
    public function getIgnoreBots(): string
    {
        return $this->ignore_bots;
    }

    /**
     * @param string $ignore_bots
     *
     * @return UserSetting
     */
    public function setIgnoreBots(string $ignore_bots): self
    {
        $this->ignore_bots = $ignore_bots;

        return $this;
    }

    /**
     * @return string
     */
    public function getIgnorePets(): string
    {
        return $this->ignore_pets;
    }

    /**
     * @param string $ignore_pets
     *
     * @return UserSetting
     */
    public function setIgnorePets(string $ignore_pets): self
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
