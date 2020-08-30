<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guestbook\Entity;

use Ares\Framework\Entity\Entity;
use Ares\Guild\Entity\Guild;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class Guestbook
 *
 * @package Ares\Guestbook\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="ares_guestbook")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\HasLifecycleCallbacks
 */
class Guestbook extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $content;

    /**
     * @ORM\OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private ?User $user;

    /**
     * @ORM\OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private ?User $profile;

    /**
     * @ORM\OneToOne(targetEntity="\Ares\Guild\Entity\Guild", fetch="EAGER")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    private ?Guild $guild;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $created_at;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected \DateTime $updated_at;

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
     * @return Guestbook
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getProfile(): ?User
    {
        return $this->profile;
    }

    /**
     * @param   User|null  $profile
     *
     * @return Guestbook
     */
    public function setProfile(?User $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Guild|null
     */
    public function getGuild(): ?Guild
    {
        return $this->guild;
    }

    /**
     * @param   Guild|null  $guild
     *
     * @return Guestbook
     */
    public function setGuild(?Guild $guild): self
    {
        $this->guild = $guild;

        return $this;
    }


    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param   string  $content
     *
     * @return Guestbook
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

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
     * @return Guestbook
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @param   \DateTime  $created_at
     *
     * @return Guestbook
     */
    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param   \DateTime  $updated_at
     *
     * @return Guestbook
     */
    public function setUpdatedAt(\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Gets triggered only on insert
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
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
            'content' => $this->getContent(),
            'created_at' => $this->getCreatedAt()
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