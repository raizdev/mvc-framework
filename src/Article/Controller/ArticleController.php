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
     * @param   ArticleRepository       $articleRepository
     * @param   UserRepository          $userRepository
     * @param   DoctrineSearchCriteria  $searchCriteria
     * @param   CreateArticleService    $createArticleService
     * @param   ValidationService       $validationService
     */
    public function __construct(
        ArticleRepository $articleRepository,
        UserRepository $userRepository,
        DoctrineSearchCriteria $searchCriteria,
        CreateArticleService $createArticleService,
        ValidationService $validationService
    ) {
        $this->articleRepository    = $articleRepository;
        $this->userRepository       = $userRepository;
        $this->searchCriteria       = $searchCriteria;
        $this->createArticleService = $createArticleService;
        $this->validationService    = $validationService;
    }

    /**
     * Creates new article.
     *
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws ArticleException
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserException
     * @throws ValidationException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'title'       => 'required',
            'description' => 'required',
            'content'     => 'required',
            'image'       => 'required',
            'hidden'      => 'required|numeric',
            'pinned'      => 'required|numeric'
        ]);

        $user = $this->getUser($this->userRepository, $request, false);

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
        $slug = (string) $args['slug'];

        /** @var Article $article */
        $article = $this->articleRepository->findBySlug($slug);

        if (is_null($article)) {
            throw new ArticleException(__('No specific Article found'), 404);
        }

        return $this->respond(
            $response,
            response()
                ->setData($article)
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function pinned(Request $request, Response $response): Response
    {
        $this->searchCriteria
            ->addFilter('pinned', Article::IS_PINNED)
            ->addFilter('hidden', Article::IS_VISIBLE);

        /** @var ArrayCollection $pinnedArticles */
        $pinnedArticles = $this->articleRepository->getList($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $pinnedArticles->toArray()
                )
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
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

        $this->searchCriteria
            ->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addOrder('id', 'DESC');

        $articles = $this->articleRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $articles->toArray()
                )
        );
    }

    /**
     * Deletes specific article.
     *
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws ArticleException
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->articleRepository->delete((int) $id);

        if (!$deleted) {
            throw new ArticleException(__('Article could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
