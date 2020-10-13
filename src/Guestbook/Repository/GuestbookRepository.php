<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guestbook\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Guestbook\Entity\Guestbook;

/**
 * Class GuestbookRepository
 *
 * @package Ares\Guestbook\Repository
 */
class GuestbookRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_GUESTBOOK_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_GUESTBOOK_COLLECTION_';

    /** @var string */
    protected string $entity = Guestbook::class;
}
