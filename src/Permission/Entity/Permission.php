<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Permission\Entity;

use Ares\Framework\Entity\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Class Permission
 *
 * @package Ares\Permission\Entity
 *
 * @ORM\Table(name="permissions")
 * @ORM\Entity
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Permission extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $rank_name;

    /**
     * @ORM\Column(type="string")
     */
    private string $badge;

    /**
     * @OneToMany(targetEntity="\Ares\User\Entity\User", mappedBy="rank_data")
     */
    private ?Collection $user_with_rank;

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
     * @return Permission
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getRankName(): ?string
    {
        return $this->rank_name;
    }

    /**
     * @param   string  $rank
     *
     * @return Permission
     */
    public function setRankName(string $rank): self
    {
        $this->rank_name = $rank;

        return $this;
    }

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->badge;
    }

    /**
     * @param   string  $badge
     *
     * @return Permission
     */
    public function setBadge(string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getUserWithRank(): ?Collection
    {
        return $this->user_with_rank;
    }

    /**
     * @param Collection|null $userWithRank
     *
     * @return Permission
     */
    public function setUserWithRank(?Collection $userWithRank): self
    {
        $this->user_with_rank = $userWithRank;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'rank_name' => $this->getRankName(),
            'badge' => $this->getBadge(),
            'user' => $this->getUserWithRank()->toArray()
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
