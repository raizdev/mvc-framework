<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity\Gift;

use Ares\Framework\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DailyGift
 *
 * @package Ares\User\Entity\Gift
 *
 * @ORM\Entity
 * @ORM\Table(name="ares_gifts")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class DailyGift extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", length=20)
     */
    private string $user_id;

    /**
     * @ORM\Column(type="integer", length=20)
     */
    private string $amount;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private string $pick_time;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return DailyGift
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     * @return DailyGift
     */
    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return DailyGift
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return int
     */
    public function getPickTime(): int
    {
        return $this->pick_time;
    }

    /**
     * @param  int $pickTime
     * @return DailyGift
     */
    public function setPickTime(int $pickTime): self
    {
        $this->pick_time = $pickTime;

        return $this;
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
            'amount' => $this->getAmount(),
            'pick_time' => $this->getPickTime()
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