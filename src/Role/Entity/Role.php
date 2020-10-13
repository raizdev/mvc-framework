<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Entity\Entity;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 *
 * @package Ares\Role\Entity
 *
 * @ORM\Table(name="ares_roles")
 * @ORM\Entity(repositoryClass="Ares\Role\Repository\RoleRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Role extends Entity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private ?string $name;

    /**
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private string $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private DateTime $updatedAt;

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
     * @return Role
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param   string  $name
     *
     * @return Role
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param   string  $description
     *
     * @return Role
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param   bool  $status
     *
     * @return Role
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param   DateTime  $createdAt
     *
     * @return Role
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param   DateTime  $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PrePersist
     *
     * @throws \Exception
     */
    public function prePersist(): void
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @ORM\PreUpdate
     *
     * @throws \Exception
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTime();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id'          => $this->getId(),
            'name'        => $this->getName(),
            'description' => $this->getDescription(),
            'status'      => $this->getStatus(),
            'created_at'  => $this->getCreatedAt(),
            'updated_at'  => $this->getUpdatedAt()
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
