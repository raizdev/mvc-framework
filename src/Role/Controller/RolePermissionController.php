<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException as ValidationExceptionAlias;
use Ares\Framework\Service\ValidationService;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Service\CreateRolePermissionService;
use Ares\Role\Service\CreatePermissionService;
use Ares\Role\Service\FetchUserPermissionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RolePermissionController
 *
 * @package Ares\Role\Controller
 */
class RolePermissionController extends BaseController
{
    /**
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * @var CreatePermissionService
     */
    private CreatePermissionService $createPermissionService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var CreateRolePermissionService
     */
    private CreateRolePermissionService $createRolePermissionService;

    /**
     * @var FetchUserPermissionService
     */
    private FetchUserPermissionService $fetchUserPermissionService;

    /**
     * RolePermissionController constructor.
     *
     * @param PermissionRepository        $permissionRepository
     * @param CreatePermissionService     $createPermissionService
     * @param CreateRolePermissionService $createRolePermissionService
     * @param FetchUserPermissionService  $fetchUserPermissionService
     * @param ValidationService           $validationService
     */
    public function __construct(
        PermissionRepository $permissionRepository,
        CreatePermissionService $createPermissionService,
        CreateRolePermissionService $createRolePermissionService,
        FetchUserPermissionService $fetchUserPermissionService,
        ValidationService $validationService
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->createPermissionService = $createPermissionService;
        $this->createRolePermissionService = $createRolePermissionService;
        $this->fetchUserPermissionService = $fetchUserPermissionService;
        $this->validationService = $validationService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $permissions = $this->permissionRepository
            ->getPaginatedPermissionList(
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($permissions)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     */
    public function userPermissions(Request $request, Response $response): Response
    {
        /** @var int $userId */
        $userId = user($request)->getId();

        $customResponse = $this->fetchUserPermissionService->execute($userId);

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
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws ValidationExceptionAlias
     */
    public function createPermission(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'name' => 'required'
        ]);

        $customResponse = $this->createPermissionService->execute($parsedData);

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
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws ValidationExceptionAlias
     */
    public function createRolePermission(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'name' => 'required'
        ]);

        $customResponse = $this->createRolePermissionService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function deleteRolePermission(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->permissionRepository->delete((int) $id);

        if (!$deleted) {
            throw new RoleException(__('Permission could not be deleted'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
