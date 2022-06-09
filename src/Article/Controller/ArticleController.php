<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Controller;

use Ares\Article\Entity\Contract\ArticleInterface;
use Ares\Article\Service\CreateArticleService;
use Ares\Article\Service\DeleteArticleService;
use Ares\Article\Service\EditArticleService;
use Ares\Framework\Controller\BaseController;
use Ares\Article\Entity\Article;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Mapping\Annotation as AR;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ares\Framework\Middleware\AuthMiddleware;

/**
 * Class ArticleController
 *
 * @AR\Router
 * @AR\Group(
 *     prefix="articles",
 *     pattern="articles",
 *     middleware={AuthMiddleware::class}
 * )
 *
 * @package Ares\Article\Controller
 */
class ArticleController extends BaseController
{
    /**
     * ArticleController constructor.
     *
     * @param ArticleRepository    $articleRepository
     * @param CreateArticleService $createArticleService
     * @param EditArticleService   $editArticleService
     * @param ValidationService    $validationService
     * @param DeleteArticleService $deleteArticleService
     */
    public function __construct(
        private readonly ArticleRepository    $articleRepository,
        private readonly CreateArticleService $createArticleService,
        private readonly EditArticleService   $editArticleService,
        private readonly ValidationService    $validationService,
        private readonly DeleteArticleService $deleteArticleService
    ) {}

    /**
     * Creates new article.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ArticleException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws NoSuchEntityException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            ArticleInterface::COLUMN_TITLE => 'required',
            ArticleInterface::COLUMN_DESCRIPTION => 'required',
            ArticleInterface::COLUMN_CONTENT => 'required',
            ArticleInterface::COLUMN_IMAGE => 'required',
            ArticleInterface::COLUMN_THUMBNAIL => 'required',
            ArticleInterface::COLUMN_HIDDEN => 'required|numeric',
            ArticleInterface::COLUMN_PINNED => 'required|numeric'
        ]);

        /** @var User $user */
        $userId = user($request)->getId();

        $customResponse = $this->createArticleService->execute($userId, $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @AR\Route(
     *     methods={"GET"},
     *     placeholders={"id": "[0-9]+"},
     *     pattern="/{id}"
     * )
     *
     * @param Request  $request
     * @param Response $response
     *
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function article(Request $request, Response $response, array $args): Response
    {
        /** @var string $slug */
        $slug = $args['slug'];

        /** @var Article $article */
        $article = $this->articleRepository->getArticleWithCommentCount($slug);

        return $this->respond(
            $response,
            response()
                ->setData($article)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws ValidationException|ArticleException
     */
    public function editArticle(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            ArticleInterface::COLUMN_ID => 'required|numeric',
            ArticleInterface::COLUMN_TITLE => 'required',
            ArticleInterface::COLUMN_DESCRIPTION => 'required',
            ArticleInterface::COLUMN_CONTENT => 'required',
            ArticleInterface::COLUMN_IMAGE => 'required',
            ArticleInterface::COLUMN_THUMBNAIL => 'required',
            ArticleInterface::COLUMN_HIDDEN => 'required|numeric',
            ArticleInterface::COLUMN_PINNED => 'required|numeric'
        ]);

        $customResponse = $this->editArticleService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Gets all Pinned Articles
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws BindingResolutionException
     * @throws DataObjectManagerException
     */
    public function pinned(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedCollection $pinnedArticles */
        $pinnedArticles = $this->articleRepository
            ->getPaginatedPinnedArticles(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($pinnedArticles)
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @param array $args
     *
     * @return Response
     * @throws DataObjectManagerException|BindingResolutionException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedCollection $articles */
        $articles = $this->articleRepository
            ->getPaginatedArticleList(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($articles)
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @param array $args
     *
     * @return Response
     * @throws DataObjectManagerException|BindingResolutionException
     */
    public function allList(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedCollection $articles */
        $articles = $this->articleRepository->getPaginatedArticleList(
            $page,
            $resultPerPage,
            true,
            true
        );

        return $this->respond($response, response()->setData($articles));
    }

    /**
     * Deletes specific article.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     * @throws ArticleException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteArticleService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
