<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Helper;

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
     * @return bool
     */
    public function buildConnection(): bool
    {
        $host = $this->config->get('hotel_settings.client.rcon_host');
        $port = $this->config->get('hotel_settings.client.rcon_port');

        $socket = $this->createSocket(AF_INET, SOCK_STREAM, SOL_TCP);

        return $result = $this->connectToSocket($socket, $host, $port);
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
    private function createSocket($domain, $type, $protocol): bool
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
    private function connectToSocket($socket, $host, $port): bool
    {
        return socket_connect($socket, $host, $port);
    }
}
