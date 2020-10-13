<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Permission\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Permission\Repository\PermissionRepository;
use Doctrine\Common\Collections\Criteria;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
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
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * PermissionController constructor.
     *
     * @param PermissionRepository   $permissionRepository
     * @param DoctrineSearchCriteria $searchCriteria
     */
    public function __construct(
        PermissionRepository $permissionRepository,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->permissionRepository = $permissionRepository;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function listUserWithRank(Request $request, Response $response, $args): Response
    {
        $this->searchCriteria
            ->addOrder('id', 'DESC');

        $users = $this->permissionRepository->getList($this->searchCriteria, false);
        $criteria = Criteria::create()
            ->andWhere(
                Criteria::expr()
                    ->gt('id', '3')
            );

        return $this->respond(
            $response,
            response()
                ->setData(
                    $users
                        ->matching($criteria)
                        ->toArray()
                )
        );
    }
}
