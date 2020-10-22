<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Entity\GuildMember;
use Doctrine\ORM\Query\Expr\Join;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class GuildRepository
 *
 * @package Ares\Guild\Repository
 */
class GuildRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_GUILD_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_GUILD_COLLECTION_';

    /** @var string */
    protected string $entity = Guild::class;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return int|mixed|string
     */
    public function profileGuilds(SearchCriteriaInterface $searchCriteria)
    {
        return $this->createPaginatedQueryBuilder()
            ->addPagination($searchCriteria->getPage(), $searchCriteria->getLimit())
            ->select('g.id, g.name, g.description, g.badge')
            ->from(Guild::class, 'g')
            ->join(
                GuildMember::class,
                'm',
                Join::WITH,
                'g.id = m.guild'
            )
            ->getQuery()
            ->getResult();
    }

    /**
     * Searchs guilds by search term.
     *
     * @param string $term
     * @return int|mixed|string
     */
    public function searchGuilds(string $term): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('g.id, g.name, g.description, g.badge, count(gm.guild) as online')
            ->from(Guild::class, 'g')
            ->leftJoin(
                GuildMember::class,
                'gm',
                Join::WITH,
                'g.id = gm.guild'
            )
            ->where('g.name LIKE :term')
            ->orderBy('online', 'DESC')
            ->groupBy('g.id')
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
    public function getPaginatedGuildList(int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('user')
            ->addRelation('room')
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
