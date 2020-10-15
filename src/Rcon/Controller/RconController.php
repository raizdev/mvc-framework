<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Service\CreateRconCommandService;
use Ares\Rcon\Service\DeleteRconCommandService;
use Ares\Rcon\Service\ExecuteRconCommandService;
use Ares\Role\Exception\RoleException;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\Query\QueryException;
use JsonException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RconController
 *
 * @package Ares\Rcon\Controller
 */
class RconController extends BaseController
{
    /**
     * @var ExecuteRconCommandService
     */
    private ExecuteRconCommandService $executeRconCommandService;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * @var DeleteRconCommandService
     */
    private DeleteRconCommandService $deleteRconCommandService;

    /**
     * @var CreateRconCommandService
     */
    private CreateRconCommandService $createRconCommandService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * RconController constructor.
     *
     * @param ExecuteRconCommandService $executeRconCommandService
     * @param DeleteRconCommandService  $deleteRconCommandService
     * @param CreateRconCommandService  $createRconCommandService
     * @param UserRepository            $userRepository
     * @param ValidationService         $validationService
     * @param DoctrineSearchCriteria    $searchCriteria
     */
    public function __construct(
        ExecuteRconCommandService $executeRconCommandService,
        DeleteRconCommandService $deleteRconCommandService,
        CreateRconCommandService $createRconCommandService,
        UserRepository $userRepository,
        ValidationService $validationService,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->executeRconCommandService = $executeRconCommandService;
        $this->searchCriteria = $searchCriteria;
        $this->deleteRconCommandService = $deleteRconCommandService;
        $this->createRconCommandService = $createRconCommandService;
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RconException
     * @throws UserException
     * @throws ValidationException
     * @throws QueryException
     * @throws JsonException|RoleException
     */
    public function executeCommand(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'command' => 'required'
        ]);

        $userId = $this->getUser($this->userRepository, $request)->getId();

        $customResponse = $this->executeRconCommandService->execute($userId, $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
