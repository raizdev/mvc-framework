<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Model\Rcon;
use Ares\Rcon\Repository\RconRepository;

/**
 * Class ExecuteRconCommandService
 *
 * @package Ares\Rcon\Service
 */
class ExecuteRconCommandService
{
    /**
     * @var RconRepository
     */
    private RconRepository $rconRepository;

    /**
     * @var Rcon
     */
    private Rcon $rcon;

    /**
     * ExecuteRconCommandService constructor.
     *
     * @param RconRepository $rconRepository
     * @param Rcon           $rcon
     */
    public function __construct(
        RconRepository $rconRepository,
        Rcon $rcon
    ) {
        $this->rconRepository = $rconRepository;
        $this->rcon = $rcon;
    }

    /**
     * @TODO Add Role Based Command Execution
     *
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws RconException
     */
    public function execute(array $data): CustomResponseInterface
    {
        $existingCommand = $this->rconRepository->getOneBy([
            'command' => $data['command']
        ]);

        if (!$existingCommand) {
            throw new RconException(__('Could not found the given command to execute'), 404);
        }

        $executeCommand = $this->rcon
            ->buildConnection()
            ->sendCommand(
                $this->rcon->getSocket(),
                $data['command'],
                $data['param'] ?? null,
                $data['value'] ?? null
            );

        return response()
            ->setData($executeCommand);
    }
}
