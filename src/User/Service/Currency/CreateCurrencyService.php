<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Currency;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Ares\User\Entity\UserCurrency;
use Ares\User\Repository\UserCurrencyRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreateCurrencyService
 *
 * @package Ares\User\Service\Currency
 */
class CreateCurrencyService
{
    /**
     * @var UserCurrencyRepository
     */
    private UserCurrencyRepository $userCurrencyRepository;

    /**
     * CreateCurrencyService constructor.
     *
     * @param UserCurrencyRepository $userCurrencyRepository
     */
    public function __construct(
        UserCurrencyRepository $userCurrencyRepository
    ) {
        $this->userCurrencyRepository = $userCurrencyRepository;
    }

    /**
     * Creates new user currency by given data.
     *
     * @param User $user
     * @param int  $type
     * @param int  $amount
     *
     * @return CustomResponseInterface
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(User $user, int $type, int $amount): CustomResponseInterface
    {
        $userCurrency = $this->userCurrencyRepository
            ->save(
                $this->getNewUserCurrency(
                    $user,
                    $type,
                    $amount)
            );

        return response()->setData($userCurrency);
    }

    /**
     * Returns new user currency object.
     *
     * @param User $user
     * @param int $type
     * @param int $amount
     * @return UserCurrency
     */
    private function getNewUserCurrency(User $user, int $type, int $amount): UserCurrency
    {
        $userCurrency = new UserCurrency();

        return $userCurrency
            ->setUser($user)
            ->setType($type)
            ->setAmount($amount);
    }
}
