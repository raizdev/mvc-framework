<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Rcon\Entity\Rcon;
use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Repository\RconRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateRconCommandService
 *
 * @package Ares\Rcon\Service
 */
class CreateRconCommandService
{
    /**
     * @var RconRepository
     */
    private RconRepository $rconRepository;

    /**
     * CreateRconCommandService constructor.
     *
     * @param RconRepository $rconRepository
     */
    public function __construct(
        RconRepository $rconRepository
    ) {
        $this->rconRepository = $rconRepository;
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws RconException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(array $data): CustomResponseInterface
    {
        $existingCommand = $this->rconRepository->getOneBy([
            'command' => $data['command']
        ]);

        if ($existingCommand) {
            throw new RconException(__('There is already an existing Command'));
        }

        $command = $this->getNewCommand($data);

        /** @var Rcon $command */
        $command = $this->rconRepository->save($command);

        return response()
            ->setData($command);
    }

    /**
     * @param array $data
     *
     * @return Rcon
     */
    private function getNewCommand(array $data): Rcon
    {
        $rconCommand = new Rcon();

        return $rconCommand
            ->setCommand($data['command'])
            ->setTitle($data['title'])
            ->setDescription($data['description']);
    }
}
