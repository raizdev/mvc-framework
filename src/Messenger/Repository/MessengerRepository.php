<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Messenger\Entity\MessengerFriendship;

/**
 * Class MessengerRepository
 *
 * @package Ares\Messenger\Repository
 */
class MessengerRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_MESSENGER_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_MESSENGER_COLLECTION_';

    /** @var string */
    protected string $entity = MessengerFriendship::class;
}
