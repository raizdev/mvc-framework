<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */


namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\User\Repository\UserRepository;
use Ares\User\Repository\UserSettingRepository;

/**
 * Class UserHallOfFameController
 *
 * @package Ares\User\Controller
 */
class UserHallOfFameController extends BaseController
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserSettingRepository
     */
    private UserSettingRepository $userSettingRepository;

    /**
     * UserHallOfFameController constructor.
     *
     * @param   UserRepository         $userRepository
     * @param   UserSettingRepository  $userSettingRepository
     */
    public function __construct(
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userSettingRepository = $userSettingRepository;
    }
}