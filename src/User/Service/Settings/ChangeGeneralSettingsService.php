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
use Ares\User\Entity\UserSetting;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Repository\UserSettingRepository;

/**
 * Class ChangeGeneralSettingsService
 *
 * @package Ares\User\Service\Settings
 */
class ChangeGeneralSettingsService
{
    /**
     * @var UserSettingRepository
     */
    private UserSettingRepository $userSettingRepository;

    /**
     * ChangeGeneralSettingsService constructor.
     *
     * @param UserSettingRepository $userSettingRepository
     */
    public function __construct(
        UserSettingRepository $userSettingRepository
    ) {
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * Changes user general settings by given user.
     *
     * @param User  $user
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws UserSettingsException
     * @throws DataObjectManagerException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        /** @var UserSetting $userSetting */
        $userSetting = $this->userSettingRepository->get($user->getId(), 'user_id');

        if (!$userSetting) {
            throw new UserSettingsException(__('Settings for given user does not exist.'));
        }

        /** @var UserSetting $userSetting */
        $userSetting = $this->userSettingRepository->save($this->getUpdatedUserSettings($userSetting, $data));

        return response()
            ->setData($userSetting);
    }

    /**
     * Returns updated user settings model.
     *
     * @param UserSetting $userSetting
     * @param array $data
     * @return UserSetting
     */
    private function getUpdatedUserSettings(UserSetting $userSetting, array $data): UserSetting
    {
        return $userSetting
            ->setBlockFollowing((string)$data['block_following'])
            ->setBlockFriendrequests((string)$data['block_friendrequests'])
            ->setBlockRoominvites((string)$data['block_roominvites'])
            ->setBlockCameraFollow((string)$data['block_camera_follow'])
            ->setBlockAlerts((string)$data['block_alerts'])
            ->setIgnoreBots((string)$data['ignore_bots'])
            ->setIgnorePets((string)$data['ignore_pets']);
    }
}
