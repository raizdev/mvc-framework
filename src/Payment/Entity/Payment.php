<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Payment\Entity;

use Ares\Framework\Entity\Entity;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class Payment
 *
 * @package Ares\Payment\Entity
 *
 * @ORM\Table(name="ares_payments")
 * @ORM\Entity(repositoryClass="Ares\Payment\Repository\PaymentRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Payment extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @OneToOne(targetEntity="\Ares\User\Entity\User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private ?User $user;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private string $code;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $processed;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $type;

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
     * @return Payment
     */
    public function setId(int $id): self
    {
        $this->id = $id;

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
     * @param   User|null  $user
     *
     * @return Payment
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param   string  $code
     *
     * @return Payment
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function getProcessed(): int
    {
        return $this->processed;
    }

    /**
     * @param   int  $processed
     *
     * @return Payment
     */
    public function setProcessed(int $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param   int  $type
     *
     * @return Payment
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'user' => $this->getUser(),
            'code' => $this->getCode(),
            'processed' => $this->getProcessed(),
            'type' => $this->getType()
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