<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository\Gift;

use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\Gift\DailyGift;

/**
 * Class DailyGiftRepository
 *
 * @package Ares\User\Repository\Gift
 */
class DailyGiftRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_USER_DAILY_GIFT_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_USER_DAILY_GIFT_COLLECTION_';

    /** @var string */
    protected string $entity = DailyGift::class;
}