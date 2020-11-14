<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Messenger\Entity\MessengerFriendship;

/**
 * Class MessengerRepository
 *
 * @package Ares\Messenger\Repository
 */
class MessengerRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_MESSENGER_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_MESSENGER_COLLECTION_';

    /** @var string */
    protected string $entity = MessengerFriendship::class;

    /**
     * @param int $userId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return array|null
     * @throws DataObjectManagerException
     */
    public function getPaginatedMessengerFriends(int $userId, int $page, int $resultPerPage): ?array
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('user_one_id', $userId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        $list = $this->getPaginatedList($searchCriteria, $page, $resultPerPage)->items();

        return $this->sortFriends($list);
    }

    /**
     * Sorts our friend list by online in our user relation subarray
     *
     * @param $list
     *
     * @return array|null
     */
    private function sortFriends(?array $list): ?array
    {
        array_multisort(
            array_map(function($user) {
                return $user->user->online;
            }, $list), SORT_DESC, $list);

        return $list;
    }
}
