<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace App\Exception;

/**
 * Class UserNotFoundException
 *
 * @package App\Exception
 */
class UserNotFoundException extends BaseException
{
    public $message = 'The user you requested does not exist.';
}
