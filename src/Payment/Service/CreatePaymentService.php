<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Payment\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Payment\Entity\Payment;
use Ares\Payment\Exception\PaymentException;
use Ares\Payment\Repository\PaymentRepository;
use Ares\User\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CreatePaymentService
 *
 * @package Ares\Payment\Service
 */
class CreatePaymentService
{

    /**
     * @var PaymentRepository
     */
    private PaymentRepository $paymentRepository;

    /**
     * CreatePaymentService constructor.
     *
     * @param   PaymentRepository  $paymentRepository
     */
    public function __construct(
        PaymentRepository $paymentRepository
    ) {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return CustomResponseInterface
     * @throws PaymentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        $payment = $this->getNewPayment($user, $data);

        /** @var Payment $existingPayment */
        $existingPayment = $this->paymentRepository->getOneBy([
            'user' => $payment->getUser(),
            'processed' => 0
        ]);

        if ($existingPayment) {
            throw new PaymentException(__('You already have an ongoing payment, wait till its processed'));
        }

        /** @var Payment $payment */
        $payment = $this->paymentRepository->save($payment);

        return response()
            ->setData($payment);
    }

    /**
     * @param   User   $user
     * @param   array  $data
     *
     * @return Payment
     */
    public function getNewPayment(User $user, array $data): Payment
    {
        $payment = new Payment();

        return $payment
            ->setCode($data['code'])
            ->setUser($user)
            ->setProcessed(0)
            ->setType(0);
    }
}
