<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Gift;

use Ares\Framework\Exception\CacheException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\Gift\DailyGift;
use Ares\User\Entity\User;
use Ares\User\Exception\Gift\DailyGiftException;
use Ares\User\Repository\Gift\DailyGiftRepository;
use Ares\User\Repository\UserRepository;
use Exception;

/**
 * Class PickGiftService
 *
 * @package Ares\User\Service\Gift
 */
class PickGiftService
{
    /**
     * @var DailyGiftRepository
     */
    private DailyGiftRepository $dailyGiftRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * PickGiftService constructor.
     *
     * @param DailyGiftRepository $dailyGiftRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        DailyGiftRepository $dailyGiftRepository,
        UserRepository $userRepository
    ) {
        $this->dailyGiftRepository = $dailyGiftRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Checks and fetches user daily gift.
     *
     * @param User $user
     *
     * @return CustomResponseInterface
     * @throws CacheException
     * @throws DailyGiftException
     * @throws DataObjectManagerException
     */
    public function execute(User $user): CustomResponseInterface
    {
        $searchCriteria = $this->dailyGiftRepository
            ->getDataObjectManager()
            ->where('user_id', $user->getId());

        /** @var DailyGift $dailyGift */
        $dailyGift = $this->dailyGiftRepository
            ->getList($searchCriteria)
            ->first();

        if (!$dailyGift) {
            $dailyGift = $this->getNewDailyGift($user);

            return response()
                ->setData($dailyGift);
        }

        $pickTime = $dailyGift->getPickTime();

        if (time() <= $pickTime) {
            throw new DailyGiftException(__('User already picked the daily gift.'), 409);
        }

        $this->applyGift($dailyGift, $user, $dailyGift->getAmount());

        return response()
            ->setData($dailyGift);
    }

    /**
     * Returns random gift amount.
     *
     * @return int
     * @throws Exception
     */
    private function getRandomGiftAmount(): int
    {
        return random_int(3000, 5000);
    }

    /**
     * Applies gift to user.
     *
     * @param DailyGift $dailyGift
     * @param User      $user
     * @param int       $amount
     *
     * @throws DataObjectManagerException
     */
    private function applyGift(DailyGift $dailyGift, User $user, int $amount): void
    {
        $credits = $user->getCredits();
        $credits += $amount;

        $user->setCredits($credits);
        $dailyGift->setPickTime(strtotime('+1 day'));

        $this->userRepository->save($user);
        $this->dailyGiftRepository->save($dailyGift);
    }

    /**
     * Saves and returns new daily gift.
     *
     * @param User $user
     *
     * @return DailyGift
     * @throws DataObjectManagerException
     * @throws Exception
     */
    private function getNewDailyGift(User $user): DailyGift
    {
        $dailyGift = new DailyGift();

        $dailyGift
            ->setUserId($user->getId())
            ->setPickTime(strtotime('+1 day'))
            ->setAmount($this->getRandomGiftAmount());

        $credits = $user->getCredits();
        $credits += $this->getRandomGiftAmount();

        $user->setCredits($credits);

        $this->dailyGiftRepository->save($dailyGift);
        $this->userRepository->save($user);

        return $dailyGift;
    }
}
