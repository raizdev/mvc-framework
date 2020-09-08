<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Payment\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Payment\Entity\Payment;

/**
 * Class PaymentRepository
 *
 * @package Ares\Payment\Repository
 */
class PaymentRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_PAYMENT_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_PAYMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Payment::class;
}