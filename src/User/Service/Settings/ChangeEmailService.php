<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Settings;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Repository\UserRepository;

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
     * @param User   $user
     * @param string $email
     * @param string $password
     *
     * @return CustomResponseInterface
     * @throws UserSettingsException
     * @throws DataObjectManagerException
     */
    public function execute(User $user, string $email, string $password): CustomResponseInterface
    {
        $currentEmail = $user->getMail();

        if (!password_verify($password, $user->getPassword())) {
            throw new UserSettingsException(__('Given old password does not match the current password'));
        }

        if ($currentEmail === $email) {
            throw new UserSettingsException(__('Given E-Mail should be different to current E-Mail'));
        }

        /** @var User $emailExists */
        $emailExists = $this->userRepository->get($email, 'mail');

        if ($emailExists) {
            throw new UserSettingsException(__('User with given E-Mail already exists'));
        }

        $this->userRepository->save($user->setMail($email));

        return response()
            ->setData($user);
    }
}
