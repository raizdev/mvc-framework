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
 * Class Permission
 *
 * @package Ares\Role\Entity
 *
 * @ORM\Table(name="ares_role_hierarchy",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="ares_role_hierarchy_unique", columns={"parent_role_id", "child_role_id"})},
 *      indexes={@ORM\Index(name="fk_role_hierarchy_child",columns={"child_role_id"}),
 *      @ORM\Index(name="IDX_AB8EFB72A44B56EA",columns={"parent_role_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class RoleHierarchy extends Entity
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(name="parent_role_id", type="integer", nullable=false)
     */
    private int $parentRoleId;

    /**
     * @ORM\Column(name="child_role_id", type="integer", nullable=false)
     */
    private int $childRoleId;

    /**
     * @ORM\ManyToOne(targetEntity="Ares\Role\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="child_role_id", referencedColumnName="id")
     * })
     */
    private Role $childRole;

    /**
     * @ORM\ManyToOne(targetEntity="Ares\Role\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_role_id", referencedColumnName="id")
     * })
     */
    private Role $parentRole;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return RoleHierarchy
     */#
    public function setId($id): self
    {
        $this->id = $id;

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
     * @return RoleHierarchy
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Role
     */
    public function getChildRole(): Role
    {
        return $this->childRole;
    }

    /**
     * @param   Role  $childRole
     *
     * @return RoleHierarchy
     */
    public function setChildRole($childRole): self
    {
        $this->childRole = $childRole;

        return $this;
    }

    /**
     * @return Role
     */
    public function getParentRole(): Role
    {
        return $this->parentRole;
    }

    /**
     * @param   Role  $parentRole
     *
     * @return RoleHierarchy
     */
    public function setParentRole($parentRole): self
    {
        $this->parentRole = $parentRole;

        return $this;
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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [];
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
