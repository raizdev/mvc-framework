<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\GuildMember;

/**
 * Class GuildMemberRepository
 *
 * @package Ares\Guild\Repository
 */
class GuildMemberRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_GUILD_MEMBER_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_GUILD_MEMBER_COLLECTION_';

    /** @var string */
    protected string $entity = GuildMember::class;

    /**
     * @return array
     */
    public function getMemberCountByGuild(): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('g.id, count(m.guild) as member')
            ->from('Ares\Guild\Entity\Guild', 'g')
            ->leftJoin(
                'Ares\Guild\Entity\GuildMember',
                'm',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'g.id = m.guild'
            )
            ->groupBy('g.id')
            ->orderBy('member', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
