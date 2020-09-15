<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Model;

use Ares\Rcon\Helper\RconHelper;

/**
 * Class Rcon
 *
 * @package Ares\Rcon\Model
 */
class Rcon
{
    private array $commands = [];

    /**
     * @var RconHelper
     */
    private RconHelper $rconHelper;

    /**
     * Rcon constructor.
     *
     * @param RconHelper $rconHelper
     */
    public function __construct(
        RconHelper $rconHelper
    ) {
        $this->rconHelper = $rconHelper;
    }

    public function addOperations(array $operation): self
    {
        $this->commands = $operation;

        return $this;
    }

    public function getOperations(): array
    {
        return $this->commands;
    }
}
