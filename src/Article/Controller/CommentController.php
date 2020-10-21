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
use Ares\Article\Service\EditCommentService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
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
     * @var EditCommentService
     */
    private EditCommentService $editCommentService;

    /**
     * CommentController constructor.
     *
     * @param CommentRepository    $commentRepository
     * @param ValidationService    $validationService
     * @param CreateCommentService $createCommentService
     * @param EditCommentService   $editCommentService
     * @param UserRepository       $userRepository
     */
    public function __construct(
        CommentRepository $commentRepository,
        ValidationService $validationService,
        CreateCommentService $createCommentService,
        EditCommentService $editCommentService,
        UserRepository $userRepository
    ) {
        $this->commentRepository    = $commentRepository;
        $this->validationService    = $validationService;
        $this->createCommentService = $createCommentService;
        $this->editCommentService   = $editCommentService;
        $this->userRepository       = $userRepository;
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws CacheException
     * @throws CommentException
     * @throws DataObjectManagerException
     * @throws UserException
     * @throws ValidationException
     */
    public function create(Request $request, Response $response, $args): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'content'    => 'required',
            'article_id' => 'required|numeric'
        ]);

        /** @var User $user */
        $user = $this->getUser($this->userRepository, $request);

        $customResponse = $this->createCommentService->execute($user->getId(), $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CommentException
     * @throws ValidationException
     * @throws CacheException
     * @throws DataObjectManagerException
     */
    public function edit(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'comment_id' => 'required|numeric',
            'content'    => 'required'
        ]);

        $customResponse = $this->editCommentService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $articleId */
        $articleId = $args['article_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $searchCriteria = $this->commentRepository
            ->getDataObjectManager()
            ->where('article_id', (int) $articleId)
            ->orderBy('id', 'DESC');

        $comments = $this->commentRepository
            ->getPaginatedList($searchCriteria, (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($comments)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws CommentException
     * @throws DataObjectManagerException
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
