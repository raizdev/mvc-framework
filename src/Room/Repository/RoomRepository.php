<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Room\Entity\Room;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class RoomRepository
 *
 * @package Ares\Room\Repository
 */
class RoomRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROOM_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROOM_COLLECTION_';

    /** @var string */
    protected string $entity = Room::class;

    /**
     * Searchs rooms by search term.
     *
     * @param string $term
     * @return int|mixed|string
     */
    public function searchRooms(string $term): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('r.id, r.name, r.description, r.users as members')
            ->from(Room::class, 'r')
            ->where('r.name LIKE :term')
            ->orderBy('members', 'DESC')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedRoomList(int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('guild')
            ->addRelation('user')
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $ownerId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return LengthAwarePaginator
     */
    public function getUserRoomsPaginatedList(int $ownerId, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('owner_id', $ownerId)
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @return mixed
     */
    public function getMostVisitedRoom(): ?Room
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('users', 'DESC');

        /** @var Room $room */
        return $this->getList($searchCriteria)->first();
    }
}
