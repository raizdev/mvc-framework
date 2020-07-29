<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\News\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\News\Entity\News;
use Ares\News\Exception\NewsException;
use Ares\News\Repository\NewsRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class NewsController
 *
 * @package Ares\User\Controller
 */
class NewsController extends BaseController
{
    /**
     * @var NewsRepository
     */
    private NewsRepository $newsRepository;

    /**
     * NewsController constructor.
     *
     * @param NewsRepository $newsRepository
     */
    public function __construct(
        NewsRepository $newsRepository
    ) {
        $this->newsRepository = $newsRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $id
     *
     * @return Response
     * @throws NewsException
     */
    public function news(Request $request, Response $response, $id): Response
    {
        /** @var News $article */
        $article = $this->newsRepository->get($id);

        if(is_null($article)) {
            throw new NewsException(__('No specific News found'));
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
     */
    public function slide(Request $request, Response $response): Response
    {
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws NewsException
     */
    public function list(Request $request, Response $response): Response
    {
        /** @var News $articles */
        $articles = $this->newsRepository->getList();

        if(is_null($articles)) {
            throw new NewsException(__('No News were found'), 404);
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
