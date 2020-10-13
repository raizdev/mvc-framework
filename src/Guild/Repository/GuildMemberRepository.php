<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\GuildMember;
use Ares\Guild\Entity\Guild;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class GuildMemberRepository
 *
 * @package Ares\Guild\Repository
 */
class GuildMemberRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_GUILD_MEMBER_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_GUILD_MEMBER_COLLECTION_';

    /** @var string */
    protected string $entity = GuildMember::class;

    /**
     * @return array
     */
    public function getMemberCountByGuild(): array
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('g.id, count(m.guild) as member')
            ->from(Guild::class, 'g')
            ->leftJoin(
                GuildMember::class,
                'm',
                Join::WITH,
                'g.id = m.guild'
            )
            ->groupBy('g.id')
            ->orderBy('member', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }
}
