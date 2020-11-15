<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Messenger\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
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
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedMessengerFriends(int $userId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('user_one_id', $userId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
