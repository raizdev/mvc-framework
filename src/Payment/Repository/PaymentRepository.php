<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Payment\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Payment\Entity\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class PaymentRepository
 *
 * @package Ares\Payment\Repository
 */
class PaymentRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_PAYMENT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_PAYMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Payment::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedPayments(int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('user')
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
