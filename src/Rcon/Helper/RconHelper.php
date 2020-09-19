<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Helper;

use Ares\Rcon\Exception\RconException;
use PHLAK\Config\Config;

/**
 * Class RconHelper
 *
 * @package Ares\Rcon\Helper
 */
class RconHelper
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * RconHelper constructor.
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Builds the Socket connection
     *
     * @return RconHelper
     * @throws RconException
     */
    public function buildConnection(): self
    {
        /** @var string $host */
        $host = $this->config->get('hotel_settings.client.rcon_host');
        /** @var int $port */
        $port = $this->config->get('hotel_settings.client.rcon_port');

        $socket = $this->createSocket(
            AF_INET,
            SOCK_STREAM,
            SOL_TCP
        );

        if (!$socket) {
            throw new RconException(__('Could not create the Socket'), 409);
        }

        $this->connectToSocket($socket, $host, $port);

        return $this;
    }

    /**
     * @param        $socket
     * @param string $command
     *
     * @return false|string
     * @throws RconException
     */
    public function sendCommand($socket, string $command)
    {
        $executor = socket_write($socket, $command, strlen($command));

        if (!$executor) {
            throw new RconException(__('Could not send the Command'));
        }

        return socket_read($socket, 2048);
    }

    /**
     * Creates a Socket
     *
     * @param $domain
     * @param $type
     * @param $protocol
     *
     * @return false|resource
     */
    public function createSocket($domain, $type, $protocol): bool
    {
        return socket_create($domain, $type, $protocol);
    }

    /**
     * Connects to our Socket
     *
     * @param $socket
     * @param $host
     * @param $port
     *
     * @return bool
     */
    public function connectToSocket($socket, $host, $port): bool
    {
        return socket_connect($socket, $host, $port);
    }
}
