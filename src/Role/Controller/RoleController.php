<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\Role\Exception\RoleException;
use Ares\Role\Service\CreateChildRoleService;
use Ares\Role\Service\CreateRoleService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RoleController
 *
 * @package Ares\Role\Controller
 */
class RoleController extends BaseController
{
    /**
     * @var CreateRoleService
     */
    private CreateRoleService $createRoleService;

    /**
     * @var CreateChildRoleService
     */
    private CreateChildRoleService $createChildRoleService;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * RoleController constructor.
     *
     * @param CreateRoleService      $createRoleService
     * @param CreateChildRoleService $createChildRoleService
     * @param ValidationService      $validationService
     * @param DoctrineSearchCriteria $searchCriteria
     */
    public function __construct(
        CreateRoleService $createRoleService,
        CreateChildRoleService $createChildRoleService,
        ValidationService $validationService,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->createRoleService = $createRoleService;
        $this->createChildRoleService = $createChildRoleService;
        $this->validationService = $validationService;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ValidationException
     * @throws RoleException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function createRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'name'       => 'required',
            'status'     => 'numeric'
        ]);

        $customResponse = $this->createRoleService->execute($parsedData);

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
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoleException
     * @throws ValidationException
     */
    public function createChildRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'parent_role_id'    => 'numeric|required',
            'child_role_id'     => 'numeric|required'
        ]);

        $customResponse = $this->createChildRoleService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    public function deleteRole()
    {

    }

    public function assignRole()
    {

    }
}
