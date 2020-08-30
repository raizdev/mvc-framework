<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Controller;

use Ares\Article\Exception\CommentException;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Repository\VoteRepository;
use Ares\Vote\Service\CreateVoteService;
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
    private CreateVoteService $createLikeService;

    /**
     * VoteController constructor.
     *
     * @param VoteRepository $voteRepository
     * @param UserRepository $userRepository
     * @param ValidationService $validationService
     * @param CreateVoteService $createLikeService
     */
    public function __construct(
        VoteRepository $voteRepository,
        UserRepository $userRepository,
        ValidationService $validationService,
        CreateVoteService $createLikeService
    ) {
        $this->voteRepository = $voteRepository;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->createLikeService = $createLikeService;
    }

    /**
     * Create new vote.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ValidationException
     * @throws UserException
     * @throws VoteException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'entity_id' => 'required|numeric',
            'vote_entity' => 'required|numeric',
            'vote_type' => 'required|numeric'
        ]);

        $user = $this->getUser($this->userRepository, $request, false);

        $customResponse = $this->createLikeService->execute($user, $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Delete vote.
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws CommentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        $id = (int) $args['id'];

        $deleted = $this->voteRepository->delete($id);

        if (!$deleted) {
            throw new CommentException(__('Vote could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()->setData(true)
        );
    }
}