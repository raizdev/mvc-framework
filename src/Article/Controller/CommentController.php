<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Controller;

use Ares\Article\Exception\CommentException;
use Ares\Article\Repository\CommentRepository;
use Ares\Article\Service\CreateCommentService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
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
 * @package Ares\Article\Controller
 */
class CommentController extends BaseController
{
    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var CreateCommentService
     */
    private CreateCommentService $createCommentService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * CommentController constructor.
     *
     * @param CommentRepository $commentRepository
     * @param DoctrineSearchCriteria $searchCriteria
     * @param ValidationService $validationService
     * @param CreateCommentService $createCommentService
     * @param UserRepository $userRepository
     */
    public function __construct(
        CommentRepository $commentRepository,
        DoctrineSearchCriteria $searchCriteria,
        ValidationService $validationService,
        CreateCommentService $createCommentService,
        UserRepository $userRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->searchCriteria = $searchCriteria;
        $this->validationService = $validationService;
        $this->createCommentService = $createCommentService;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ValidationException
     */
    public function create(Request $request, Response $response, $args): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'content' => 'required',
            'article_id' => 'required|numeric'
        ]);

        $user = $this->getUser($this->userRepository, $request, false);

        $customResponse = $this->createCommentService->execute($user, $parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws ValidationException
     */
    public function edit(Request $request, Response $response, $args): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'comment_id' => 'required|numeric',
            'content' => 'required'
        ]);

        $comment = $this->commentRepository->get($parsedData['comment_id']);

        $comment
            ->setContent($parsedData['content'])
            ->setIsEdited(1);

        $comment = $this->commentRepository->update($comment);

        return $this->respond(
            $response,
            response()->setData($comment)
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws CommentException
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addOrder('id', 'DESC');

        /** @var ArrayCollection $pinnedArticles */
        $comments = $this->commentRepository->paginate($this->searchCriteria);

        if ($comments->isEmpty()) {
            throw new CommentException(__('No Comments were found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($comments->toArray())
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws CommentException
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ValidationException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $id = $args['id'];
        
        $deleted = $this->commentRepository->delete($id);

        if (!$deleted) {
            throw new CommentException(__('Comment could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()->setData(true)
        );
    }
}
