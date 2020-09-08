<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserSetting;

/**
 * Class UserSettingRepository
 *
 * @package Ares\User\Repository
 */
class UserSettingRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_USER_SETTING_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_USER_SETTING_COLLECTION_';

    /** @var string */
    protected string $entity = UserSetting::class;
}