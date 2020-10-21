<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Controller;

use Ares\Forum\Entity\Topic;
use Ares\Forum\Exception\TopicException;
use Ares\Forum\Repository\TopicRepository;
use Ares\Forum\Service\Topic\CreateTopicService;
use Ares\Forum\Service\Topic\EditTopicService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\CacheException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class TopicController
 *
 * @package Ares\Forum\Controller
 */
class TopicController extends BaseController
{
    /**
     * @var TopicRepository
     */
    private TopicRepository $topicRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var CreateTopicService
     */
    private CreateTopicService $createTopicService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var EditTopicService
     */
    private EditTopicService $editTopicService;

    /**
     * TopicController constructor.
     *
     * @param   TopicRepository         $topicRepository
     * @param   UserRepository          $userRepository
     * @param   CreateTopicService      $createTopicService
     * @param   EditTopicService        $editTopicService
     * @param   ValidationService       $validationService
     */
    public function __construct(
        TopicRepository $topicRepository,
        UserRepository $userRepository,
        CreateTopicService $createTopicService,
        EditTopicService $editTopicService,
        ValidationService $validationService
    ) {
        $this->topicRepository    = $topicRepository;
        $this->userRepository     = $userRepository;
        $this->createTopicService = $createTopicService;
        $this->editTopicService   = $editTopicService;
        $this->validationService  = $validationService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CacheException
     * @throws DataObjectManagerException
     * @throws TopicException
     * @throws ValidationException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'title'       => 'required',
            'description' => 'required',
        ]);

        $customResponse = $this->createTopicService->execute($parsedData);

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
     * @throws CacheException
     * @throws DataObjectManagerException
     * @throws TopicException
     * @throws ValidationException
     */
    public function edit(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'topic_id' => 'required|numeric',
            'title'    => 'required',
            'content'  => 'required'
        ]);

        /** @var Topic $topic */
        $topic = $this->editTopicService->execute($parsedData);

        return $this->respond(
            $response,
            response()
                ->setData($topic)
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
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $searchCriteria = $this->topicRepository
            ->getDataObjectManager()
            ->orderBy('id', 'DESC');

        /** @var Topic $topic */
        $topic = $this->topicRepository
            ->getPaginatedList($searchCriteria, (int) $page, (int) $resultPerPage);

        return $this->respond(
            $response,
            response()
                ->setData($topic)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws TopicException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->topicRepository->delete((int) $id);

        if (!$deleted) {
            throw new TopicException(__('Topic could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
