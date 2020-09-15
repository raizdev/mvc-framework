<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Interfaces;

/**
 * Interface RconInterface
 *
 * @package Ares\Rcon\Interfaces
 */
interface RconInterface
{
    /**
     * @param array $operation
     */
    public function addOperation(array $operation): void;
}
