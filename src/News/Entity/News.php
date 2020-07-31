<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\News\Entity;

use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class News
 *
 * @package Ares\Framework\Entity
 *
 * @ORM\Entity(repositoryClass="Ares\News\Repository\NewsRepository")
 * @ORM\Table(name="ares_news", uniqueConstraints={@ORM\UniqueConstraint(name="title", columns={"title"})}))
 * @ORM\HasLifecycleCallbacks
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $content;

    /**
     * @ORM\Column(type="string", length=35)
     */
    private ?string $image;

    /**
     * @ORM\Column(type="integer", length=20)
     */
    private int $author;

    /**
     * @ORM\Column(type="integer", length=20)
     */
    private int $hidden;

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
     * Gets Title of News
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return News
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets Description of News
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return News
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return News
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return News
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAuthor(): ?int
    {
        return $this->author;
    }

    /**
     * @param int $author
     *
     * @return News
     */
    public function setAuthor(int $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return int
     */
    public function getHidden(): ?int
    {
        return $this->hidden;
    }

    /**
     * @param int $hidden
     *
     * @return News
     */
    public function setHidden(int $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Gets triggered only on insert
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
    }

    /**
     * Returns a copy of the current Entity safely
     *
     * @return array
     */
    public function getArrayCopy(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'author' => $this->author,
            'hidden' => $this->hidden,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
