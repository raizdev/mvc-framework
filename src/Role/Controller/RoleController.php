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
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Service\AssignUserToRoleService;
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
     * @var RoleRepository
     */
    private RoleRepository $roleRepository;

    /**
     * @var AssignUserToRoleService
     */
    private AssignUserToRoleService $assignUserToRoleService;

    /**
     * RoleController constructor.
     *
     * @param CreateRoleService       $createRoleService
     * @param CreateChildRoleService  $createChildRoleService
     * @param AssignUserToRoleService $assignUserToRoleService
     * @param ValidationService       $validationService
     * @param DoctrineSearchCriteria  $searchCriteria
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        CreateRoleService $createRoleService,
        CreateChildRoleService $createChildRoleService,
        AssignUserToRoleService $assignUserToRoleService,
        ValidationService $validationService,
        DoctrineSearchCriteria $searchCriteria,
        RoleRepository $roleRepository
    ) {
        $this->createRoleService = $createRoleService;
        $this->createChildRoleService = $createChildRoleService;
        $this->assignUserToRoleService = $assignUserToRoleService;
        $this->validationService = $validationService;
        $this->searchCriteria = $searchCriteria;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria
            ->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addOrder('id', 'DESC');

        $roles = $this->roleRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $roles->toArray()
                )
        );
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
            'name' => 'required'
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
            'parent_role_id' => 'numeric|required',
            'child_role_id' => 'numeric|required'
        ]);

        $customResponse = $this->createChildRoleService->execute($parsedData);

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
    public function assignRole(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'user_id' => 'numeric|required',
            'role_id' => 'numeric|required'
        ]);

        $customResponse = $this->assignUserToRoleService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoleException
     */
    public function deleteRole(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->roleRepository->delete((int)$id);

        if (!$deleted) {
            throw new RoleException(__('Role could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
