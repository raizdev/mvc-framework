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
    protected string $password;

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
     */
    public function setUsername($username): void
    {
        $this->username = $username;
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
     */
    public function setTicket($ticket): void
    {
        $this->auth_ticket = $ticket;
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
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }
}