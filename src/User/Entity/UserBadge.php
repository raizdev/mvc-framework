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

/**
 * Class UserBadge
 *
 * @package Ares\User\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="users_badges")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class UserBadge extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private ?User $user;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $slot_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $badge_code;

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
     * @return UserBadge
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
     * @return UserBadge
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getSlot(): int
    {
        return $this->slot_id;
    }

    /**
     * @param   int  $slot
     *
     * @return UserBadge
     */
    public function setSlot(int $slot): self
    {
        $this->slot_id = $slot;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->badge_code;
    }

    /**
     * @param   string  $code
     *
     * @return UserBadge
     */
    public function setCode(string $code): self
    {
        $this->badge_code = $code;

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
            'slot' => $this->getSlot(),
            'code' => $this->getCode(),
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
     * @param   string  $data
     */
    public function unserialize($data): void
    {
        $values = unserialize($data);

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}