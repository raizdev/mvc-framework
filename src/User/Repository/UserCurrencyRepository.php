<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserCurrency;

/**
 * Class UserCurrencyRepository
 *
 * @package Ares\User\Repository
 */
class UserCurrencyRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_USER_CURRENCY';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_CURRENCY_COLLECTION_';

    /** @var string */
    protected string $entity = UserCurrency::class;
}
