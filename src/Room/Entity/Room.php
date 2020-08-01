<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Room
 *
 * @package Ares\Room\Entity
 *
 * @ORM\Entity
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $owner;

    /**
     * @ORM\Column(type="integer", length=50)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private string $description;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('open','locked','password', 'invisible')")
     */
    private string $state;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $users;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $users_max;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $guild;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $score;

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
     * @return Room
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwner(): int
    {
        return $this->owner;
    }

    /**
     * @param int $owner
     *
     * @return Room
     */
    public function setOwner(int $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Room
     */
    public function setName(string $name): self
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
     * @param string $description
     *
     * @return Room
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return Room
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsers(): int
    {
        return $this->users;
    }

    /**
     * @param int $users
     *
     * @return Room
     */
    public function setUsers(int $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsersMax(): int
    {
        return $this->users_max;
    }

    /**
     * @param int $users_max
     *
     * @return Room
     */
    public function setUsersMax(int $users_max): self
    {
        $this->users_max = $users_max;

        return $this;
    }

    /**
     * @return int
     */
    public function getGuild(): int
    {
        return $this->guild;
    }

    /**
     * @param int $guild
     *
     * @return Room
     */
    public function setGuild(int $guild): self
    {
        $this->guild = $guild;

        return $$this;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     *
     * @return Room
     */
    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Returns a copy of the current Entity safely
     *
     * @return array
     */
    public function getArrayCopy(): array
    {
        return [
            'id' => $this->getId(),
            'owner' => $this->getOwner(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'state' => $this->getState(),
            'users' => $this->getUsers(),
            'users_max' => $this->getUsersMax(),
            'guild' => $this->getGuild(),
            'score' => $this->getScore()
        ];
    }
}
