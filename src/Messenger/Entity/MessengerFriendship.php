<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Messenger\Entity\Contract\MessengerFriendshipInterface;

/**
 * Class MessengerFriendship
 *
 * @package Ares\Messenger\Entity
 */
class MessengerFriendship extends DataObject implements MessengerFriendshipInterface
{
    /** @var string */
    public const TABLE = 'messenger_friendships';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return MessengerFriendship
     */
    public function setId(int $id): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserOneId(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_USER_ONE_ID);
    }

    /**
     * @param int $user_one_id
     *
     * @return MessengerFriendship
     */
    public function setUserOneId(int $user_one_id): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_USER_ONE_ID, $user_one_id);
    }

    /**
     * @return int
     */
    public function getUserTwoId(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_USER_TWO_ID);
    }

    /**
     * @param int $user_two_id
     *
     * @return MessengerFriendship
     */
    public function setUserTwoId(int $user_two_id): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_USER_TWO_ID, $user_two_id);
    }

    /**
     * @return int
     */
    public function getRelation(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_RELATION);
    }

    /**
     * @param int $relation
     *
     * @return MessengerFriendship
     */
    public function setRelation(int $relation): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_RELATION, $relation);
    }

    /**
     * @return int
     */
    public function getFriendsSince(): int
    {
        return $this->getData(MessengerFriendshipInterface::COLUMN_FRIENDS_SINCE);
    }

    /**
     * @param int $friends_since
     *
     * @return MessengerFriendship
     */
    public function setFriendsSince(int $friends_since): MessengerFriendship
    {
        return $this->setData(MessengerFriendshipInterface::COLUMN_FRIENDS_SINCE, $friends_since);
    }
}
