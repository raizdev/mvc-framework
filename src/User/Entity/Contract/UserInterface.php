<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity\Contract;

/**
 * Interface UserInterface
 *
 * @package Ares\User\Entity\Contract
 */
interface UserInterface
{
    public const COLUMN_ID = 'id';

    public const ROLES = 'roles';
    public const PERMISSIONS = 'permissions';

    public const COLUMN_USERNAME = 'username';
    public const COLUMN_REAL_NAME = 'real_name';
    public const COLUMN_PASSWORD = 'password';
    public const COLUMN_MAIL = 'mail';
    public const COLUMN_MAIL_VERIFIED = 'mail_verified';
    public const COLUMN_ACCOUNT_CREATED = 'account_created';
    public const COLUMN_ACCOUNT_DAY_OF_BIRTH = 'account_day_of_birth';
    public const COLUMN_LAST_LOGIN = 'last_login';
    public const COLUMN_LAST_ONLINE = 'last_online';
    public const COLUMN_MOTTO = 'motto';
    public const COLUMN_LOOK = 'look';
    public const COLUMN_GENDER = 'gender';
    public const COLUMN_RANK = 'rank';
    public const COLUMN_CREDITS = 'credits';
    public const COLUMN_PIXELS = 'pixels';
    public const COLUMN_POINTS = 'points';
    public const COLUMN_ONLINE = 'online';
    public const COLUMN_AUTH_TICKET = 'auth_ticket';
    public const COLUMN_IP_REGISTER = 'ip_register';
    public const COLUMN_IP_CURRENT = 'ip_current';
    public const COLUMN_MACHINE_ID = 'machine_id';
    public const COLUMN_HOME_ROOM = 'home_room';
    public const COLUMN_USER_LIKES = 'user_likes';
    public const COLUMN_PIN = 'pin';
    public const COLUMN_TEAMRANK = 'teamrank';
    public const COLUMN_FBID = 'fbid';
    public const COLUMN_FBENABLE = 'fbenable';
    public const COLUMN_GOOGLE_SECRET_CODE = 'google_secret_code';
    public const COLUMN_2FA_STATUS = '2fa_status';
    public const COLUMN_REMEMBER_TOKEN = 'remember_token';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
    public const COLUMN_NAME_COLOUR = 'name_colour';
    public const COLUMN_LOCALE = 'locale';
    public const COLUMN_PICKED_DAILY_GIFT = 'picked_daily_gift';
    public const COLUMN_ROLE_ID = 'role_id';
}
