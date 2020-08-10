<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Article\Entity\Article;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ArticleController
 *
 * @package Ares\Article\Controller
 */
class ArticleController extends BaseController
{
    /*
     * Represents the Value of pinned Articles
     */
    private const IS_PINNED = 1;

    /*
     * Represents the Value of Visible Articles
    */
    private const IS_VISIBLE = 1;

    /**
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;

    /**
     * NewsController constructor.
     *
     * @param ArticleRepository $articleRepository
     */
    public function __construct(
        ArticleRepository $articleRepository
    ) {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws ArticleException
     */
    public function article(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Article $article */
        $article = $this->articleRepository->get($id);

        if (is_null($article)) {
            throw new ArticleException(__('No specific Article found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($article->getArrayCopy())
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ArticleException
     */
    public function pinned(Request $request, Response $response): Response
    {
        /** @var array $pinnedArticles */
        $pinnedArticles = $this->articleRepository->getList([
            'pinned' => self::IS_PINNED,
            'hidden' => self::IS_VISIBLE
        ]);

        if (empty($pinnedArticles)) {
            throw new ArticleException(__('No Pinned Articles found'));
        }

        /** @var ArrayCollection $list */
        $list = [];
        foreach ($pinnedArticles as $pinnedArticle) {
            $list[] = $pinnedArticle->getArrayCopy();
        }

        return $this->respond(
            $response,
            response()->setData($list)
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
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedArrayCollection */
        $articles = $this->articleRepository->findPageBy(
            (int)$page,
            (int)$resultPerPage,
            ['hidden' => self::IS_VISIBLE],
            ['id' => 'DESC']
        );

        if ($articles->isEmpty()) {
            throw new ArticleException(__('No Articles were found'), 404);
        }

        /** @var PaginatedArrayCollection $list */
        $list = [];
        foreach ($articles as $article) {
            $list[] = $article->getArrayCopy();
        }

        return $this->respond(
            $response,
            response()->setData($list)
        );
    }
}
