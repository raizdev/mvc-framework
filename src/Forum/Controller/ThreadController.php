<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Controller;

use Ares\Forum\Exception\ThreadException;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Forum\Service\Thread\CreateThreadService;
use Ares\Forum\Service\Thread\EditThreadService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\SimpleCache\InvalidArgumentException;

class ThreadController extends BaseController
{
    /**
     * @var ThreadRepository
     */
    private ThreadRepository $threadRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var CreateThreadService
     */
    private CreateThreadService $createThreadService;

    /**
     * @var EditThreadService
     */
    private EditThreadService $editThreadService;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * CommentController constructor.
     *
     * @param ThreadRepository       $threadRepository
     * @param UserRepository         $userRepository
     * @param CreateThreadService    $createThreadService
     * @param EditThreadService      $editThreadService
     * @param ValidationService      $validationService
     * @param DoctrineSearchCriteria $searchCriteria
     */
    public function __construct(
        ThreadRepository $threadRepository,
        UserRepository $userRepository,
        CreateThreadService $createThreadService,
        EditThreadService $editThreadService,
        ValidationService $validationService,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->threadRepository = $threadRepository;
        $this->userRepository = $userRepository;
        $this->createThreadService = $createThreadService;
        $this->editThreadService = $editThreadService;
        $this->validationService = $validationService;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ThreadException
     * @throws ValidationException
     * @throws UserException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
            'topic_id' => 'required|numeric'
        ]);

        $user = $this->getUser($this->userRepository, $request, false);

        $customResponse = $this->createThreadService->execute($user, $parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param          $args
     *
     * @return Response
     * @throws PhpfastcacheSimpleCacheException
     * @throws ThreadException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function thread(Request $request, Response $response, $args)
    {
        /** @var string $slug */
        $slug = $args['slug'];

        /** @var int $topic */
        $topic = $args['topic_id'];

        /** @var Thread $thread */
        $thread = $this->threadRepository->findByCriteria($topic, $slug);

        if (!$thread) {
            throw new ThreadException(__('No specific Thread found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($thread)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var int $topic */
        $topic = $args['topic_id'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addFilter('topic', $topic)
            ->addOrder('id', 'DESC');

        $thread = $this->threadRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()->setData($thread->toArray())
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ThreadException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->threadRepository->delete((int)$id);

        if (!$deleted) {
            throw new ThreadException(__('Thread could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()->setData(true)
        );
    }
}
