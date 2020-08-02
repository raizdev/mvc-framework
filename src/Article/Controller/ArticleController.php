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
        /** @var Article $article */
        $article = $this->articleRepository->get((int)$args['id']);

        if(is_null($article)) {
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
            'pinned' => self::IS_PINNED
        ]);

        if(empty($pinnedArticles)) {
            throw new ArticleException(__('No Pinned Articles found'));
        }

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
    public function slide(Request $request, Response $response, $args): Response
    {
        $total = $args['total'] ?? 0;
        $offset = $args['offset'] ?? 0;

        $articles = $this->articleRepository->getList([], ['id' => 'DESC'], (int)$total, (int)$offset);

        if (empty($articles)) {
            throw new ArticleException(__('No Articles were found'), 404);
        }

        $list = [];
        foreach ($articles as $article) {
            $list[] = $article->getArrayCopy();
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
     * @return Response
     * @throws ArticleException
     */
    public function list(Request $request, Response $response): Response
    {
        /** @var Article $articles */
        $articles = $this->articleRepository->getList([]);

        if(empty($articles)) {
            throw new ArticleException(__('No Articles were found'), 404);
        }

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
