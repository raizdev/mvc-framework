<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Room\Entity\Room;

/**
 * Class RoomRepository
 *
 * @package Ares\Room\Repository
 */
class RoomRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_ROOM_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_ROOM_COLLECTION_';

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
            ->from('Ares\Room\Entity\Room', 'r')
            ->where('r.name LIKE :term')
            ->orderBy('members', 'DESC')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult();
    }
}
