<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Service\CreateChildPermission;
use Ares\Role\Service\CreateRolePermissionService;
use Ares\Role\Service\CreatePermissionService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
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
     * RolePermissionController constructor.
     *
     * @param PermissionRepository        $permissionRepository
     * @param CreatePermissionService     $createPermissionService
     * @param CreateRolePermissionService $createRolePermissionService
     * @param ValidationService           $validationService
     */
    public function __construct(
        PermissionRepository $permissionRepository,
        CreatePermissionService $createPermissionService,
        CreateRolePermissionService $createRolePermissionService,
        ValidationService $validationService
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->createPermissionService = $createPermissionService;
        $this->createRolePermissionService = $createRolePermissionService;
        $this->validationService = $validationService;
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
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoleException
     * @throws ValidationException
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
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoleException
     */
    public function deleteRolePermission(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->permissionRepository->delete((int)$id);

        if (!$deleted) {
            throw new RoleException(__('Permission could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
