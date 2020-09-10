<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Entity;

use Ares\Framework\Entity\Entity;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Comment
 *
 * @package Ares\Forum\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="ares_forum_comments")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\HasLifecycleCallbacks
 */
class Comment extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity="\Ares\Forum\Entity\Thread")
     */
    private ?Thread $thread;

    /**
     * @ORM\OneToOne(targetEntity="\Ares\User\Entity\User")
     */
    private ?User $user;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $is_edited;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $likes;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $dislikes;

    /**
     * @ORM\Column(type="string")
     */
    private string $content;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private \DateTime $created_at;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private \DateTime $updated_at;

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
     * @return Comment
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Thread|null
     */
    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    /**
     * @param   Thread|null  $thread
     *
     * @return Comment
     */
    public function setThread(?Thread $thread): self
    {
        $this->thread = $thread;

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
     * @return Comment
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getIsEdited(): int
    {
        return $this->is_edited;
    }

    /**
     * @param   int  $isEdited
     *
     * @return Comment
     */
    public function setIsEdited(int $isEdited): self
    {
        $this->is_edited = $isEdited;

        return $this;
    }


    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->likes;
    }

    /**
     * @param   int  $likes
     *
     * @return Comment
     */
    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->dislikes;
    }

    /**
     * @param   int  $dislikes
     *
     * @return Comment
     */
    public function setDislikes(int $dislikes): self
    {
        $this->dislikes = $dislikes;

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
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

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
     * @return Comment
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
     * @return Comment
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
    public function onPrePersist(): void
    {
        $this->created_at = new \DateTime("now");
        $this->updated_at = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
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
            'isEdited' => $this->getIsEdited(),
            'content' => $this->getContent(),
            'likes' => $this->getLikes(),
            'dislikes' => $this->getDislikes(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
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
