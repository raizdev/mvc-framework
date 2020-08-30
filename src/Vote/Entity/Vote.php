<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Entity;

use Ares\Framework\Entity\Entity;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class Vote
 *
 * @package Ares\Vote\Entity
 * @ORM\Entity
 * @ORM\Table(name="ares_votes")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\HasLifecycleCallbacks
 */
class Vote extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $entity_id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $vote_entity;

    /**
     * @ORM\Column(type="integer")
     */
    private int $vote_type;

    /**
     * @ManyToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     */
    private ?User $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Vote
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->entity_id;
    }

    /**
     * @param int $entity_id
     * @return Vote
     */
    public function setEntityId(int $entity_id): self
    {
        $this->entity_id = $entity_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getVoteEntity(): int
    {
        return $this->vote_entity;
    }

    /**
     * @param int $vote_entity
     * @return Vote
     */
    public function setVoteEntity(int $vote_entity): self
    {
        $this->vote_entity = $vote_entity;

        return $this;
    }

    /**
     * @return int
     */
    public function getVoteType(): int
    {
        return $this->vote_type;
    }

    /**
     * @param int $vote_type
     * @return Vote
     */
    public function setVoteType(int $vote_type): self
    {
        $this->vote_type = $vote_type;

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
     * @param User|null $user
     * @return Vote
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'entity_id' => $this->getEntityId(),
            'vote_entity' => $this->getVoteEntity(),
            'vote_type' => $this->getVoteType()
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
     * @param string $data
     * @return void
     */
    public function unserialize($data): void
    {
        $values = unserialize($data);

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}
