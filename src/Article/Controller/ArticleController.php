<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Controller;

use Ares\Article\Service\CreateArticleService;
use Ares\Framework\Controller\BaseController;
use Ares\Article\Entity\Article;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
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
 * Class ArticleController
 *
 * @package Ares\Article\Controller
 */
class ArticleController extends BaseController
{
    /**
     * Represents the Value of pinned Articles
     */
    private const IS_PINNED = 1;

    /**
     * Represents the Value of Visible Articles
     */
    private const IS_VISIBLE = 1;

    /**
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * @var CreateArticleService
     */
    private CreateArticleService $createArticleService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * NewsController constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param DoctrineSearchCriteria $searchCriteria
     * @param CreateArticleService $createArticleService
     * @param ValidationService $validationService
     * @param UserRepository $userRepository
     */
    public function __construct(
        ArticleRepository $articleRepository,
        DoctrineSearchCriteria $searchCriteria,
        CreateArticleService $createArticleService,
        ValidationService $validationService,
        UserRepository $userRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->searchCriteria = $searchCriteria;
        $this->createArticleService = $createArticleService;
        $this->validationService = $validationService;
        $this->userRepository = $userRepository;
    }

    /**
     * Creates new article.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws ValidationException
     * @throws UserException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Request $request, Response $response)
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'title' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'content' => 'required',
            'image' => 'required',
            'hidden' => 'required|numeric',
            'pinned' => 'required|numeric'
        ]);

        $user = $this->getUser($this->userRepository, $request);

        $customResponse = $this->createArticleService->execute($user, $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @param             $args
     *
     * @return Response
     * @throws ArticleException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function article(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = (int) $args['id'];

        /** @var Article $article */
        $article = $this->articleRepository->get((int)$id);

        if (is_null($article)) {
            throw new ArticleException(__('No specific Article found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($article)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ArticleException
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function pinned(Request $request, Response $response): Response
    {
        $this->searchCriteria
            ->addFilter('pinned', self::IS_PINNED)
            ->addFilter('hidden', self::IS_VISIBLE);

        /** @var ArrayCollection $pinnedArticles */
        $pinnedArticles = $this->articleRepository->getList($this->searchCriteria);

        if ($pinnedArticles->isEmpty()) {
            throw new ArticleException(__('No Pinned Articles found'));
        }

        return $this->respond(
            $response,
            response()->setData($pinnedArticles->toArray())
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws ArticleException
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
        $articles = $this->articleRepository->paginate($this->searchCriteria);

        if ($articles->isEmpty()) {
            throw new ArticleException(__('No Articles were found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($articles->toArray())
        );
    }

    /**
     * Deletes specific article.
     *
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
        $id = (int) $args['id'];

        $deleted = $this->articleRepository->delete($id);

        if (!$deleted) {
            throw new ArticleException(__('Article could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()->setData(true)
        );
    }
}
