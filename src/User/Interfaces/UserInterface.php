<?php
namespace StarreDEV\User\Interfaces;

/**
 * Interface UserInterface
 *
 * @package StarreDEV\Contracts\User
 */
interface UserInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USERNAME = 'username';
    public const COLUMN_PASSWORD = 'password';
    public const COLUMN_MAIL = 'mail';
    public const COLUMN_LAST_LOGIN = 'last_login';
    public const COLUMN_IP_REGISTER = 'ip_register';
    public const COLUMN_IP_CURRENT = 'ip_current';
    public const COLUMN_ACCOUNT_CREATED = 'account_created';
}