<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Repository;

use Ares\Framework\Interfaces\RepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Jhg\DoctrinePagination\ORM\PaginatedRepository;

/**
 * Class BaseRepository
 *
 * @package Ares\Framework\Repository
 */
abstract class BaseRepository extends PaginatedRepository implements RepositoryInterface
{
    /**
     * @var string
     */
    protected string $entity;

    /**
     * BaseRepository constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, new ClassMetadata($this->entity));
    }
}
