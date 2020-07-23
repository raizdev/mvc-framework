<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @package Ares\Framework\Entity
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
    private string $password;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $mail;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $look;

    /**
     * @ORM\Column(type="string", length=35)
     */
    private ?string $motto;

    /**
     * @ORM\Column(type="integer", length=20)
     */
    private int $credits;

    /**
     * @ORM\Column(type="integer", length=20)
     */
    private int $points;

    /**
     * @ORM\Column(type="integer", length=20)
     */
    private int $pixels;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private ?string $auth_ticket;

    /**
     * @ORM\Column(type="integer", length=15)
     */
    private int $account_created;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $ip_register;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private ?string $ip_current;

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
     * Gets Mail of User
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
     * @return string
     */
    public function getLook(): ?string
    {
        return $this->look;
    }

    /**
     * @param   string  $look
     *
     * @return User
     */
    public function setLook(string $look): self
    {
        $this->look = $look;

        return $this;
    }

    /**
     * @return string
     */
    public function getMotto(): ?string
    {
        return $this->motto;
    }

    /**
     * @param   string  $motto
     *
     * @return User
     */
    public function setMotto(string $motto): self
    {
        $this->motto = $motto;

        return $this;
    }

    /**
     * @return int
     */
    public function getCredits(): ?int
    {
        return $this->credits;
    }

    /**
     * @param   int  $credits
     *
     * @return User
     */
    public function setCredits(int $credits): self
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * @return int
     */
    public function getPoints(): ?int
    {
        return $this->points;
    }

    /**
     * @param   int  $points
     *
     * @return User
     */
    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return int
     */
    public function getPixels(): ?int
    {
        return $this->pixels;
    }

    /**
     * @param   int  $pixels
     *
     * @return User
     */
    public function setPixels(int $pixels): self
    {
        $this->pixels = $pixels;

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
     * @return int
     */
    public function getAccountCreated(): ?int
    {
        return $this->account_created;
    }

    /**
     * @param   int  $timestamp
     *
     * @return User
     */
    public function setAccountCreated(int $timestamp): self
    {
        $this->account_created = $timestamp;

        return $this;
    }

    /**
     * @return string
     */
    public function getIPRegister(): string
    {
        return $this->ip_register;
    }

    /**
     * @param   string  $ip
     *
     * @return User
     */
    public function setIPRegister(string $ip): self
    {
        $this->ip_register = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentIP(): string
    {
        return $this->ip_current;
    }

    /**
     * @param   string  $ip
     *
     * @return User
     */
    public function setCurrentIP(string $ip): self
    {
        $this->ip_current = $ip;

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
            'id'              => $this->id,
            'username'        => $this->username,
            'mail'            => $this->mail,
            'look'            => $this->look,
            'motto'           => $this->motto,
            'credits'         => $this->credits,
            'points'          => $this->points,
            'pixels'          => $this->pixels,
            'auth_ticket'     => $this->auth_ticket,
            'account_created' => $this->account_created
        ];
    }
}
