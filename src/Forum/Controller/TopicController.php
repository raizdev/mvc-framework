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
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\SimpleCache\InvalidArgumentException;

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
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

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
     * @param   DoctrineSearchCriteria  $searchCriteria
     */
    public function __construct(
        TopicRepository $topicRepository,
        UserRepository $userRepository,
        CreateTopicService $createTopicService,
        EditTopicService $editTopicService,
        ValidationService $validationService,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->topicRepository = $topicRepository;
        $this->userRepository = $userRepository;
        $this->createTopicService = $createTopicService;
        $this->editTopicService = $editTopicService;
        $this->validationService = $validationService;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws TopicException
     * @throws ValidationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'title' => 'required',
            'description' => 'required',
        ]);

        $customResponse = $this->createTopicService->execute($parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws TopicException
     * @throws ValidationException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function edit(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'topic_id' => 'required|numeric',
            'title' => 'required',
            'content' => 'required'
        ]);

        /** @var Topic $topic */
        $topic = $this->editTopicService->execute($parsedData);

        return $this->respond(
            $response,
            response()->setData($topic)
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
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

        $topic = $this->topicRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()->setData($topic->toArray())
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws TopicException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->topicRepository->delete((int)$id);

        if (!$deleted) {
            throw new TopicException(__('Topic could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()->setData(true)
        );
    }
}