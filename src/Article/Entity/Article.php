<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Entity;

use Ares\Article\Entity\Contract\ArticleInterface;
use Ares\Framework\Model\DataObject;

/**
 * Class Article
 *
 * @package Ares\Article\Entity
 */
class Article extends DataObject implements ArticleInterface
{
    /** @var string */
    public const TABLE = 'ares_articles';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(ArticleInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Article
     */
    public function setId(int $id): Article
    {
        return $this->setData(ArticleInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(ArticleInterface::COLUMN_TITLE);
    }

    /**
     * @param string $title
     *
     * @return Article
     */
    public function setTitle(string $title): Article
    {
        return $this->setData(ArticleInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->getData(ArticleInterface::COLUMN_SLUG);
    }

    /**
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug(string $slug): Article
    {
        return $this->setData(ArticleInterface::COLUMN_SLUG, $slug);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(ArticleInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Article
     */
    public function setDescription(string $description): Article
    {
        return $this->setData(ArticleInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getData(ArticleInterface::COLUMN_CONTENT);
    }

    /**
     * @param string $content
     *
     * @return Article
     */
    public function setContent(string $content)
    {
        return $this->setData(ArticleInterface::COLUMN_CONTENT, $content);
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->getData(ArticleInterface::COLUMN_IMAGE);
    }

    /**
     * @param string $image
     *
     * @return Article
     */
    public function setImage(string $image): Article
    {
        return $this->setData(ArticleInterface::COLUMN_IMAGE, $image);
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->getData(ArticleInterface::COLUMN_AUTHOR_ID);
    }

    /**
     * @param int $author_id
     *
     * @return Article
     */
    public function setAuthorId(int $author_id): Article
    {
        return $this->setData(ArticleInterface::COLUMN_AUTHOR_ID, $author_id);
    }

    /**
     * @return int
     */
    public function getHidden(): int
    {
        return $this->getData(ArticleInterface::COLUMN_HIDDEN);
    }

    /**
     * @param int $hidden
     *
     * @return Article
     */
    public function setHidden(int $hidden): Article
    {
        return $this->setData(ArticleInterface::COLUMN_HIDDEN, $hidden);
    }

    /**
     * @return int
     */
    public function getPinned(): int
    {
        return $this->getData(ArticleInterface::COLUMN_PINNED);
    }

    /**
     * @param int $pinned
     *
     * @return Article
     */
    public function setPinned(int $pinned): Article
    {
        return $this->setData(ArticleInterface::COLUMN_PINNED, $pinned);
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->getData(ArticleInterface::COLUMN_LIKES);
    }

    /**
     * @param int $likes
     *
     * @return Article
     */
    public function setLikes(int $likes): Article
    {
        return $this->setData(ArticleInterface::COLUMN_LIKES, $likes);
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->getData(ArticleInterface::COLUMN_DISLIKES);
    }

    /**
     * @param int $dislikes
     *
     * @return Article
     */
    public function setDislikes(int $dislikes): Article
    {
        return $this->setData(ArticleInterface::COLUMN_DISLIKES, $dislikes);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(ArticleInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $created_at
     *
     * @return Article
     */
    public function setCreatedAt(\DateTime $created_at): Article
    {
        return $this->setData(ArticleInterface::COLUMN_CREATED_AT, $created_at);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(ArticleInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updated_at
     *
     * @return Article
     */
    public function setUpdatedAt(\DateTime $updated_at): Article
    {
        return $this->setData(ArticleInterface::COLUMN_UPDATED_AT, $updated_at);
    }
}
