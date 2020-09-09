<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Service\Votes;

use Ares\Vote\Exception\VoteException;
use Ares\Vote\Interfaces\VoteTypeInterface;
use Ares\Vote\Service\GetVoteEntityService;
use Exception;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class DecrementVoteService
 *
 * @package Ares\Vote\Service\Votes
 */
class DecrementVoteService
{
    /**
     * @var GetVoteEntityService
     */
    private GetVoteEntityService $getVoteEntityService;

    /**
     * DecrementVoteService constructor.
     *
     * @param GetVoteEntityService $getVoteEntityService
     */
    public function __construct(
        GetVoteEntityService $getVoteEntityService
    ) {
        $this->getVoteEntityService = $getVoteEntityService;
    }

    /**
     * Decrements votes by given data.
     *
     * @param int $entityId
     * @param int $voteEntity
     * @param int $voteType
     *
     * @return bool
     * @throws VoteException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(int $entityId, int $voteEntity, int $voteType): bool
    {
        $entityRepository = $this->getVoteEntityService->execute($entityId, $voteEntity);
        $entity = $entityRepository->get($entityId, false);

        if ($voteType === VoteTypeInterface::VOTE_LIKE) {
            $likes = $entity->getLikes();
            $likes = $likes > 0 ? --$likes : $likes;

            $entity->setLikes($likes);
        } else {
            $dislikes = $entity->getDislikes();
            $dislikes = $dislikes > 0 ? --$dislikes : $dislikes;

            $entity->setDislikes($dislikes);
        }

        try {
            $entityRepository->update($entity);
            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
