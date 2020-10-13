<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateRoleService
 *
 * @package Ares\Role\Service
 */
class CreateRoleService
{
    /**
     * @var RoleRepository
     */
    private RoleRepository $roleRepository;

    /**
     * CreateRoleService constructor.
     *
     * @param RoleRepository $roleRepository
     */
    public function __construct(
        RoleRepository $roleRepository
    ) {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoleException
     * @throws InvalidArgumentException
     */
    public function execute(array $data): CustomResponseInterface
    {
        $existingRole = $this->roleRepository->getOneBy([
            'name' => $data['name']
        ]);

        if ($existingRole) {
            throw new RoleException(__('There is already a Role with that name'));
        }

        $role = $this->getNewRole($data);

        /** @var Role $role */
        $role = $this->roleRepository->save($role);

        return response()
            ->setData($role);
    }

    /**
     * @param array $data
     *
     * @return Role
     */
    private function getNewRole(array $data): Role
    {
        $role = new Role();

        $role
            ->setName($data['name'])
            ->setDescription($data['description']);

        return $role;
    }
}
