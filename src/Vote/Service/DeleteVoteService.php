<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Ares\Vote\Entity\Vote;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Repository\VoteRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;

/**
 * Class DeleteVoteService
 *
 * @package Ares\Vote\Service
 */
class DeleteVoteService
{
    /**
     * @var VoteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * DeleteVoteService constructor.
     *
     * @param VoteRepository $voteRepository
     */
    public function __construct(
        VoteRepository $voteRepository
    ) {
        $this->voteRepository = $voteRepository;
    }

    /**
     * Deletes vote by given data.
     *
     * @param User  $user
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws VoteException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        /** @var Vote $vote */
        $vote = $this->voteRepository->findOneBy([
            'entity_id' => $data['entity_id'],
            'vote_entity' => $data['vote_entity'],
            'vote_type' => $data['vote_type'],
            'user' => $user
        ]);

        if (!$vote) {
            throw new VoteException(__('Vote could not be found by given data.'), 404);
        }

        $deleted = $this->voteRepository->delete($vote->getId());

        return response()->setData($deleted);
    }
}
