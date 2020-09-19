<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Model;

use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Helper\RconHelper;

/**
 * Class Rcon
 *
 * @package Ares\Rcon\Model
 */
class Rcon
{
    /**
     * @var string
     */
    private string $socket;

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

    /**
     * @return RconHelper
     * @throws RconException
     */
    public function getConnection(): RconHelper
    {
        return $this->rconHelper->buildConnection();
    }

    /**
     * @return string
     */
    public function getSocket(): string
    {
        return $this->socket;
    }

    /**
     * @param string $socket
     */
    public function setSocket(string $socket): void
    {
        $this->socket = $socket;
    }
}
