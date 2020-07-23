<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Exception;

use Ares\Framework\Exception\BaseException;

/**
 * Class UserNotFoundException
 *
 * @package Ares\Framework\Exception
 */
class UserNotFoundException extends BaseException
{
    public $message = 'The user you requested does not exist.';
}
