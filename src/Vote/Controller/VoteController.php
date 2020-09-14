<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Repository\VoteRepository;
use Ares\Vote\Service\CreateVoteService;
use Ares\Vote\Service\DeleteVoteService;
use Ares\Vote\Service\Votes\DecrementVoteService;
use Ares\Vote\Service\Votes\IncrementVoteService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class VoteController
 *
 * @package Ares\Vote\Controller
 */
class VoteController extends BaseController
{
    /**
     * @var VoteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var CreateVoteService
     */
    private CreateVoteService $createVoteService;

    /**
     * @var DeleteVoteService
     */
    private DeleteVoteService $deleteVoteService;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $doctrineSearchCriteria;

    /**
     * @var IncrementVoteService
     */
    private IncrementVoteService $incrementVoteService;

    /**
     * @var DecrementVoteService
     */
    private DecrementVoteService $decrementVoteService;

    /**
     * VoteController constructor.
     *
     * @param   VoteRepository          $voteRepository
     * @param   UserRepository          $userRepository
     * @param   ValidationService       $validationService
     * @param   CreateVoteService       $createVoteService
     * @param   DeleteVoteService       $deleteVoteService
     * @param   DoctrineSearchCriteria  $doctrineSearchCriteria
     * @param   IncrementVoteService    $incrementVoteService
     * @param   DecrementVoteService    $decrementVoteService
     */
    public function __construct(
        VoteRepository $voteRepository,
        UserRepository $userRepository,
        ValidationService $validationService,
        CreateVoteService $createVoteService,
        DeleteVoteService $deleteVoteService,
        DoctrineSearchCriteria $doctrineSearchCriteria,
        IncrementVoteService $incrementVoteService,
        DecrementVoteService $decrementVoteService
    ) {
        $this->voteRepository         = $voteRepository;
        $this->userRepository         = $userRepository;
        $this->validationService      = $validationService;
        $this->createVoteService      = $createVoteService;
        $this->deleteVoteService      = $deleteVoteService;
        $this->doctrineSearchCriteria = $doctrineSearchCriteria;
        $this->incrementVoteService   = $incrementVoteService;
        $this->decrementVoteService   = $decrementVoteService;
    }

    /**
     * Create new vote.
     *
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserException
     * @throws ValidationException
     * @throws VoteException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'entity_id'   => 'required|numeric',
            'vote_entity' => 'required|numeric',
            'vote_type'   => 'required|numeric'
        ]);

        $user = $this->getUser($this->userRepository, $request, false);

        $customResponse = $this->createVoteService->execute($user, $parsedData);

        $result = $this->incrementVoteService
            ->execute(
                $parsedData['entity_id'],
                $parsedData['vote_entity'],
                $parsedData['vote_type'],
            );

        if (!$result) {
            $this->deleteVoteService->execute($user, $parsedData);
            throw new VoteException(__('The entity could not be incremented.'), 500);
        }

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Returns total count of likes/dislikes for given entity.
     *
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserException
     */
    public function getTotalVotes(Request $request, Response $response)
    {
        $this->doctrineSearchCriteria
            ->addFilter(
                'user',
                $this->getUser(
                    $this->userRepository,
                    $request,
                    false
                )->getId()
            );

        /** @var ArrayCollection $votes */
        $votes = $this->voteRepository->getList($this->doctrineSearchCriteria, false)->toArray();

        return $this->respond(
            $response,
            response()
                ->setData($votes)
        );
    }

    /**
     * Delete vote.
     *
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserException
     * @throws ValidationException
     * @throws VoteException
     */
    public function delete(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'entity_id'   => 'required|numeric',
            'vote_entity' => 'required|numeric',
            'vote_type'   => 'required|numeric'
        ]);

        $user = $this->getUser($this->userRepository, $request, false);

        $customResponse = $this->deleteVoteService->execute($user, $parsedData);

        if (!$customResponse->getData()) {
            throw new VoteException(__('Vote could not be deleted.'), 409);
        }

        $result = $this->decrementVoteService
            ->execute(
                $parsedData['entity_id'],
                $parsedData['vote_entity'],
                $parsedData['vote_type'],
            );

        if (!$result) {
            $this->createVoteService->execute($user, $parsedData);
            throw new VoteException(__('The entity could not be incremented.'), 500);
        }

        return $this->respond(
            $response,
            $customResponse
        );
    }
}