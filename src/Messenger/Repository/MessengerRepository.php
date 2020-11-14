<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Messenger\Entity\MessengerFriendship;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedMessengerFriends(int $userId, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'messenger_friendships.id',
                'messenger_friendships.relation',
                'messenger_friendships.friends_since',
                'users.id',
                'users.username',
                'users.motto',
                'users.look',
                'users.online'
            ])->leftJoin(
                'users',
                'messenger_friendships.user_two_id',
                '=',
                'users.id'
            )->where('messenger_friendships.user_one_id', $userId)
            ->orderBy('users.online', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
