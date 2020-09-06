<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Settings;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class ChangeEmailService
 *
 * @package Ares\User\Service\Settings
 */
class ChangeEmailService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * ChangeEmailService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Changes email by given data.
     *
     * @param User $user
     * @param string $email
     * @param string $password
     * @return CustomResponseInterface
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserSettingsException
     */
    public function execute(User $user, string $email, string $password): CustomResponseInterface
    {
        $currentEmail = $user->getMail();

        if (!password_verify($password, $user->getPassword())) {
            throw new UserSettingsException(__('Given old password does not match the current password.'));
        }

        if ($currentEmail === $email) {
            throw new UserSettingsException(__('Given email should be different to current email.'));
        }

        $emailExists = $this->userRepository->getByMail($email);

        if ($emailExists) {
            throw new UserSettingsException(__('User with given email already exists.'));
        }

        $this->userRepository->update($user->setMail($email));

        return response()->setData($user);
    }
}