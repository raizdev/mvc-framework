<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Exception;

/**
 * Class UserNotFoundException
 *
 * @package Ares\Framework\Exception
 */
class UserNotFoundException extends BaseException
{
    public $message = 'The user you requested does not exist.';
}
