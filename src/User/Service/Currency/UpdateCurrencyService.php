<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Currency;


use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\User\Entity\UserCurrency;
use Ares\User\Exception\UserCurrencyException;
use Ares\User\Repository\UserCurrencyRepository;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;

/**
 * Class UpdateCurrencyService
 *
 * @package Ares\User\Service\Currency
 */
class UpdateCurrencyService
{
    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * @var UserCurrencyRepository
     */
    private UserCurrencyRepository $userCurrencyRepository;

    /**
     * UpdateCurrencyService constructor.
     *
     * @param DoctrineSearchCriteria $searchCriteria
     * @param UserCurrencyRepository $userCurrencyRepository
     */
    public function __construct(
        DoctrineSearchCriteria $searchCriteria,
        UserCurrencyRepository $userCurrencyRepository
    ) {
        $this->searchCriteria = $searchCriteria;
        $this->userCurrencyRepository = $userCurrencyRepository;
    }

    /**
     * Updates currency by given data.
     *
     * @param int $userId
     * @param int $type
     * @param int $amount
     * @return void
     * @throws PhpfastcacheSimpleCacheException
     * @throws UserCurrencyException
     */
    public function execute(int $userId, int $type, int $amount): void
    {
        $searchCriteria = $this->searchCriteria
            ->addFilter('user', $userId)
            ->addFilter('type', $type);

        /** @var UserCurrency[] $currencies */
        $currencies = $this->userCurrencyRepository->getList($searchCriteria)->toArray();

        if (!$currencies) {
            throw new UserCurrencyException(__('Currencies was not found.'), 404);
        }

        foreach ($currencies as $currency) {
            $currency->setAmount($amount);
            try {
                $this->userCurrencyRepository->update($currency);
            } catch (\Exception $exception) {
                throw new UserCurrencyException(__('Currency could not be updated.'), 422);
            }
        }
    }
}