<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Entity\Permission;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\PermissionRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreatePermissionService
 *
 * @package Ares\Role\Service
 */
class CreatePermissionService
{
    /**
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * CreatePermissionService constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        PermissionRepository $permissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws RoleException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(array $data): CustomResponseInterface
    {
        $existingPermission = $this->permissionRepository->getOneBy([
           'name' => $data['name']
        ]);

        if ($existingPermission) {
            throw new RoleException(__('There is already a Permission with that name'));
        }

        $permission = $this->getNewPermission($data);

        /** @var Permission $permission */
        $permission = $this->permissionRepository->save($permission);

        return response()
            ->setData($permission);
    }

    /**
     * @param array $data
     *
     * @return Permission
     */
    private function getNewPermission(array $data): Permission
    {
        $permission = new Permission();

        $permission
            ->setName($data['name'])
            ->setDescription($data['description']);

        return $permission;
    }
}
