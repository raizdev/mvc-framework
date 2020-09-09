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
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateVoteService
 *
 * @package Ares\Vote\Service
 */
class CreateVoteService
{
    /**
     * @var VoteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * @var GetVoteEntityService
     */
    private GetVoteEntityService $getVoteEntityService;

    /**
     * CreateVoteService constructor.
     *
     * @param VoteRepository $voteRepository
     * @param GetVoteEntityService $getVoteEntityService
     */
    public function __construct(
        VoteRepository $voteRepository,
        GetVoteEntityService $getVoteEntityService
    ) {
        $this->voteRepository = $voteRepository;
        $this->getVoteEntityService = $getVoteEntityService;
    }

    /**
     * Create new vote with given data.
     *
     * @param User  $user
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws VoteException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        $vote = $this->getNewVote($user, $data);

        $existingVote = $this->voteRepository->findOneBy([
            'entity_id' => $vote->getEntityId(),
            'vote_entity' => $vote->getVoteEntity(),
            'user' => $user
        ]);

        if ($existingVote) {
            throw new VoteException(__('User already voted for this entity.'), 422);
        }

        $entityRepository = $this->getVoteEntityService->execute($vote->getEntityId(), $vote->getVoteEntity());
        $entity = $entityRepository->get($vote->getEntityId(), false);

        if (!$entity) {
            throw new VoteException(__('The related vote entity has no existing data.'), 404);
        }

        $vote = $this->voteRepository->save($vote);

        return response()->setData($vote);
    }

    /**
     * Return new vote.
     *
     * @param User $user
     * @param array $data
     * @return Vote
     */
    private function getNewVote(User $user, array $data): Vote
    {
        $vote = new Vote();

        return $vote
            ->setEntityId($data['entity_id'])
            ->setVoteEntity($data['vote_entity'])
            ->setVoteType($data['vote_type'])
            ->setUser($user);
    }
}
