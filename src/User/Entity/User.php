<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity;

use Ares\Framework\Entity\Entity;
use Ares\Permission\Entity\Permission;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class User
 *
 * @package Ares\User\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="username", columns={"username"})}))
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\HasLifecycleCallbacks
 */
class User extends Entity
{
    /**
     * @var int
     */
    public const USER_EQUALS_ONLINE = 2;

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
     * @ORM\Column(type="string", length=10)
     */
    private string $gender;

    /**
     * @ORM\Column(type="string", length=35)
     */
    private ?string $motto;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $credits;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private ?int $rank;

    /**
     * @ORM\OneToMany(targetEntity="\Ares\Role\Entity\RoleUser", mappedBy="user", fetch="EXTRA_LAZY")
     */
    private ?Collection $roles;

    /**
     * @ManyToOne(targetEntity="\Ares\Permission\Entity\Permission", inversedBy="user_with_rank")
     * @JoinColumn(name="rank")
     */
    private ?Permission $rank_data;

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
     * @ORM\Column(type="integer", columnDefinition="ENUM('0',1','2')")
     */
    private int $online;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private ?string $locale;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $last_login;

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private ?int $last_online = 0;

    /**
     * @ORM\OneToMany(targetEntity="\Ares\User\Entity\UserCurrency", mappedBy="user", fetch="EAGER")
     */
    private Collection $currencies;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $created_at;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected \DateTime $updated_at;

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
     * @return int
     */
    public function getOnline(): int
    {
        return $this->online;
    }

    /**
     * @param int $online
     *
     * @return User
     */
    public function setOnline(int $online): self
    {
        $this->online = $online;

        return $this;
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
     * @param string $mail
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
     * @param string $look
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
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return User
     */
    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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
     * @param string $motto
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
     * @param int $credits
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
    public function getRank(): ?int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     *
     * @return User
     */
    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

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
     * @param int $timestamp
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
     * @param string $ip
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
     * @param string $ip
     *
     * @return User
     */
    public function setCurrentIP(string $ip): self
    {
        $this->ip_current = $ip;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * @param string|null $locale
     *
     * @return User
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastLogin(): int
    {
        return $this->last_login;
    }

    /**
     * @param int $last_login
     *
     * @return User
     */
    public function setLastLogin(int $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastOnline(): int
    {
        return $this->last_online;
    }

    /**
     * @param int|null $last_online
     *
     * @return User
     */
    public function setLastOnline(?int $last_online): self
    {
        $this->last_online = $last_online;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    /**
     * @param Collection $currencies
     *
     * @return User
     */
    public function setCurrencies(Collection $currencies): self
    {
        $this->currencies = $currencies;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getRoles(): ?Collection
    {
        return $this->roles;
    }

    /**
     * @param Collection|null $roles
     *
     * @return User
     */
    public function setRoles(?Collection $roles): self
    {
        $this->roles = $roles;

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
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
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
            'username' => $this->getUsername(),
            'look' => $this->getLook(),
            'motto' => $this->getMotto(),
            'credits' => $this->getCredits(),
            'rank' => $this->getRank(),
            'account_created' => $this->getAccountCreated(),
            'online' => $this->getOnline(),
            'locale' => $this->getLocale(),
            'last_login' => $this->getLastLogin(),
            'last_online' => $this->getLastOnline(),
            'roles' => $this->getRoles()->toArray(),
            'currencies' => $this->getCurrencies()->toArray(),
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
