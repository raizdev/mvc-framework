<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Vote\Entity\Vote;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Repository\VoteRepository;

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
     * @param int   $user_id
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws VoteException
     */
    public function execute(int $user_id, array $data): CustomResponseInterface
    {
        $searchCriteria = $this->voteRepository
            ->getDataObjectManager()
            ->where($data)
            ->where('user_id', $user_id);

        /** @var Vote $vote */
        $vote = $this->voteRepository
            ->getList($searchCriteria)
            ->first();

        if (!$vote) {
            throw new VoteException(__('Vote could not be found by given data.'), 404);
        }

        $deleted = $this->voteRepository->delete($vote->getId());

        return response()
            ->setData($deleted);
    }
}
