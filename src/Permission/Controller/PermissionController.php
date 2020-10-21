<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Permission\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Permission\Repository\PermissionRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class PermissionController
 *
 * @package Ares\Permission\Controller
 */
class PermissionController extends BaseController
{
    /**
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * PermissionController constructor.
     *
     * @param PermissionRepository   $permissionRepository
     */
    public function __construct(
        PermissionRepository $permissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     */
    public function listUserWithRank(Request $request, Response $response, $args): Response
    {
        $searchCriteria = $this->permissionRepository
            ->getDataObjectManager()
            ->where('id', '>', 3)
            ->orderBy('id', 'DESC');

        $users = $this->permissionRepository->getList($searchCriteria);

        return $this->respond(
            $response,
            response()
                ->setData($users)
        );
    }
}
