<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="username", columns={"username"})}))
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public int $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    public string $username;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=150)
     */
    public string $mail;

    /**
     * @ORM\Column(type="string", length=150)
     */
    public string $auth_ticket;

    /**
     * Get User id
     *
     * @ORM\return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets Username of User
     *
     * @ORM\return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param $username
     *
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Gets Auth_ticket of User
     *
     * @ORM\return string
     */
    public function getTicket(): ?string
    {
        return $this->auth_ticket;
    }

    /**
     * @param $ticket
     *
     * @return User
     */
    public function setTicket(string $ticket): self
    {
        $this->auth_ticket = $ticket;

        return $this;
    }

    /**
     * Gets Password of User
     *
     * @ORM\return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}