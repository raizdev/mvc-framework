<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Controller;

use Ares\Forum\Entity\Comment;
use Ares\Forum\Exception\CommentException;
use Ares\Forum\Repository\CommentRepository;
use Ares\Forum\Service\Comment\CreateCommentService;
use Ares\Forum\Service\Comment\EditCommentService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class CommentController
 *
 * @package Ares\Forum\Controller
 */
class CommentController extends BaseController
{
    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var CreateCommentService
     */
    private CreateCommentService $createCommentService;

    /**
     * @var EditCommentService
     */
    private EditCommentService $editCommentService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * CommentController constructor.
     *
     * @param   CommentRepository       $commentRepository
     * @param   UserRepository          $userRepository
     * @param   CreateCommentService    $createCommentService
     * @param   EditCommentService      $editCommentService
     * @param   ValidationService       $validationService
     * @param   DoctrineSearchCriteria  $searchCriteria
     */
    public function __construct(
        CommentRepository $commentRepository,
        UserRepository $userRepository,
        CreateCommentService $createCommentService,
        EditCommentService $editCommentService,
        ValidationService $validationService,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->commentRepository    = $commentRepository;
        $this->userRepository       = $userRepository;
        $this->createCommentService = $createCommentService;
        $this->editCommentService   = $editCommentService;
        $this->validationService    = $validationService;
        $this->searchCriteria       = $searchCriteria;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws CommentException
     * @throws ValidationException
     * @throws UserException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'thread_id' => 'required|numeric',
            'content'   => 'required'
        ]);

        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request, false);

        $customResponse = $this->createCommentService->execute($user, $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws CommentException
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserException
     * @throws ValidationException
     */
    public function edit(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'thread_id' => 'required|numeric',
            'content'   => 'required'
        ]);

        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request, false);

        /** @var Comment $comment */
        $comment = $this->editCommentService->execute($user, $parsedData);

        return $this->respond(
            $response,
            response()
                ->setData($comment)
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var int $thread */
        $thread = $args['thread'];

        $this->searchCriteria
            ->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addFilter('thread', (int) $thread)
            ->addOrder('id', 'DESC');

        $comments = $this->commentRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $comments->toArray()
                )
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws CommentException
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->commentRepository->delete((int) $id);

        if (!$deleted) {
            throw new CommentException(__('Comment could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
