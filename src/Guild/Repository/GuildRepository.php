<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\Guild;

/**
 * Class GuildRepository
 *
 * @package Ares\Guild\Repository
 */
class GuildRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_GUILD_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_GUILD_COLLECTION_';

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
            ->from('Ares\Guild\Entity\Guild', 'g')
            ->join(
                'Ares\Guild\Entity\GuildMember',
                'm',
                \Doctrine\ORM\Query\Expr\Join::WITH,
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
            ->from('Ares\Guild\Entity\Guild', 'g')
            ->leftJoin(
                'Ares\Guild\Entity\GuildMember',
                'gm',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'g.id = gm.guild'
            )
            ->where('g.name LIKE :term')
            ->orderBy('online', 'DESC')
            ->groupBy('g.id')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->getResult();
    }
}
