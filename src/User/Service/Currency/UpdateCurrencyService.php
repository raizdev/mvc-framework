<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Currency;

use Ares\Framework\Exception\CacheException;
use Ares\User\Entity\UserCurrency;
use Ares\User\Exception\UserCurrencyException;
use Ares\User\Repository\UserCurrencyRepository;
use Exception;

/**
 * Class UpdateCurrencyService
 *
 * @package Ares\User\Service\Currency
 */
class UpdateCurrencyService
{
    /**
     * @var UserCurrencyRepository
     */
    private UserCurrencyRepository $userCurrencyRepository;

    /**
     * UpdateCurrencyService constructor.
     *
     * @param UserCurrencyRepository $userCurrencyRepository
     */
    public function __construct(
        UserCurrencyRepository $userCurrencyRepository
    ) {
        $this->userCurrencyRepository = $userCurrencyRepository;
    }

    /**
     * Updates currency by given data.
     *
     * @param int $userId
     * @param int $type
     * @param int $amount
     *
     * @return void
     * @throws UserCurrencyException
     * @throws CacheException
     */
    public function execute(int $userId, int $type, int $amount): void
    {
        $dataObjectManager = $this->userCurrencyRepository->getDataObjectManager();

        $dataObjectManager
            ->where('user_id', $userId)
            ->where('type', $type);

        /** @var UserCurrency[] $currencies */
        $currencies = $this->userCurrencyRepository->getList($dataObjectManager);

        if (!$currencies) {
            throw new UserCurrencyException(__('Currencies was not found.'), 404);
        }

        foreach ($currencies as $currency) {
            $currency->setAmount($amount);
            try {
                $this->userCurrencyRepository->save($currency);
            } catch (Exception $exception) {
                throw new UserCurrencyException(__('Currency could not be updated.'), 422);
            }
        }
    }
}
