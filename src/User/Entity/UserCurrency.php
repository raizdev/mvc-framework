<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity;

use Ares\Framework\Model\DataObject;
use Ares\User\Entity\Contract\UserCurrencyInterface;

/**
 * Class UserCurrency
 *
 * @package Ares\User\Entity
 */
class UserCurrency extends DataObject implements UserCurrencyInterface
{
    /** @var string */
    public const TABLE = 'users_currency';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(UserCurrencyInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return UserCurrency
     */
    public function setId(int $id): UserCurrency
    {
        return $this->setData(UserCurrencyInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(UserCurrencyInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $user_id
     *
     * @return UserCurrency
     */
    public function setUserId(int $user_id): UserCurrency
    {
        return $this->setData(UserCurrencyInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->getData(UserCurrencyInterface::COLUMN_TYPE);
    }

    /**
     * @param int $type
     *
     * @return UserCurrency
     */
    public function setType(int $type): UserCurrency
    {
        return $this->setData(UserCurrencyInterface::COLUMN_TYPE, $type);
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->getData(UserCurrencyInterface::COLUMN_AMOUNT);
    }

    /**
     * @param int $amount
     *
     * @return UserCurrency
     */
    public function setAmount(int $amount): UserCurrency
    {
        return $this->setData(UserCurrencyInterface::COLUMN_AMOUNT, $amount);
    }
}
