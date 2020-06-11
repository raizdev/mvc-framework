<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @package App\Entity
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
    private int $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected string $password;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $mail;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $auth_ticket;

    /**
     * Get User id
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets Username of User
     *
     * @return string
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
     * Gets Maail of User
     *
     * @return string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param   string  $mail
     *
     * @return User
     */
    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Gets Auth_ticket of User
     *
     * @return string
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
     * @return string
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

    /**
     * Returns a copy of the current Entity safely
     *
     * @return array
     */
    public function getArrayCopy(): array
    {
        return [
            'id'          => $this->id,
            'username'    => $this->username,
            'mail'        => $this->mail,
            'auth_ticket' => $this->auth_ticket
        ];
    }
}