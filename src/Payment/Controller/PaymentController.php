<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Payment\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\Payment\Entity\Payment;
use Ares\Payment\Exception\PaymentException;
use Ares\Payment\Repository\PaymentRepository;
use Ares\Payment\Service\CreatePaymentService;
use Ares\User\Exception\UserException;
use Ares\User\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class PaymentController
 *
 * @package Ares\Payment\Controller
 */
class PaymentController extends BaseController
{
    /**
     * @var PaymentRepository
     */
    private PaymentRepository $paymentRepository;

    /**
     * @var CreatePaymentService
     */
    private CreatePaymentService $createPaymentService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * PaymentController constructor.
     *
     * @param   PaymentRepository       $paymentRepository
     * @param   UserRepository          $userRepository
     * @param   CreatePaymentService    $createPaymentService
     * @param   ValidationService       $validationService
     * @param   DoctrineSearchCriteria  $searchCriteria
     */
    public function __construct(
        PaymentRepository $paymentRepository,
        UserRepository $userRepository,
        CreatePaymentService $createPaymentService,
        ValidationService $validationService,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->userRepository = $userRepository;
        $this->createPaymentService = $createPaymentService;
        $this->validationService = $validationService;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws ValidationException
     * @throws PaymentException
     * @throws UserException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'code' => 'required',
            'type' => 'required|numeric'
        ]);

        $user = $this->getUser($this->userRepository, $request, false);

        $customResponse = $this->createPaymentService->execute($user, $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PaymentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function payment(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Payment $payment */
        $payment = $this->paymentRepository->get((int)$id);

        if (!$payment) {
            throw new PaymentException(__('No specific Payment found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($payment)
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int) $page)
            ->setLimit((int) $resultPerPage)
            ->addOrder('id', 'DESC');

        $payments = $this->paymentRepository->paginate($this->searchCriteria);

        return $this->respond(
            $response,
            response()->setData($payments->toArray())
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     * @param             $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PaymentException
     * @throws PhpfastcacheSimpleCacheException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->paymentRepository->delete((int) $id);

        if (!$deleted) {
            throw new PaymentException(__('Payment could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()->setData(true)
        );
    }
}