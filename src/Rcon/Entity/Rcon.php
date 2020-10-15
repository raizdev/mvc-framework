<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Entity;

use Ares\Framework\Entity\Entity;
use Ares\Role\Entity\Permission;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rcon
 *
 * @package Ares\Rcon\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="ares_rcon_commands")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Rcon extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $command;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $description;

    /**
     * @ORM\OneToOne(targetEntity="\Ares\Role\Entity\Permission")
     */
    private ?Permission $permission;

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
     * @return Rcon
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     *
     * @return Rcon
     */
    public function setCommand(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Rcon
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

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
     * @param string $description
     *
     * @return Rcon
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Permission|null
     */
    public function getPermission(): ?Permission
    {
        return $this->permission;
    }

    /**
     * @param Permission|null $permission
     *
     * @return Rcon
     */
    public function setPermission(?Permission $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'command' => $this->getCommand(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'permission' => $this->getPermission()->getName()
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
     */
    public function unserialize($data): void
    {
        $values = unserialize($data);

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}
