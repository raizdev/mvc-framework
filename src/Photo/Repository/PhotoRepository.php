<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Photo\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Photo\Entity\Photo;

/**
 * Class PhotoRepository
 *
 * @package Ares\Photo\Repository
 */
class PhotoRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_PHOTO_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_PHOTO_COLLECTION_';

    /** @var string */
    protected string $entity = Photo::class;
}