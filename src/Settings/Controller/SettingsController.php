<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Settings\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Framework\Service\ValidationService;
use Ares\Settings\Entity\Setting;
use Ares\Settings\Exception\SettingsException;
use Ares\Settings\Repository\SettingsRepository;
use Ares\Settings\Service\UpdateSettingsService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class SettingsController
 *
 * @package Ares\Settings\Controller
 */
class SettingsController extends BaseController
{
    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var SettingsRepository
     */
    private SettingsRepository $settingsRepository;

    /**
     * @var UpdateSettingsService
     */
    private UpdateSettingsService $updateSettingsService;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * SettingsController constructor.
     *
     * @param   ValidationService       $validationService
     * @param   SettingsRepository      $settingsRepository
     * @param   UpdateSettingsService   $updateSettingsService
     * @param   DoctrineSearchCriteria  $searchCriteria
     */
    public function __construct(
        ValidationService $validationService,
        SettingsRepository $settingsRepository,
        UpdateSettingsService $updateSettingsService,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->validationService     = $validationService;
        $this->settingsRepository    = $settingsRepository;
        $this->updateSettingsService = $updateSettingsService;
        $this->searchCriteria        = $searchCriteria;
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws ValidationException
     * @throws SettingsException
     */
    public function get(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'key' => 'required'
        ]);

        /** @var string $key */
        $key = $parsedData['key'];

        /** @var Setting $configData */
        $configData = $this->settingsRepository->getBy([
            'key' => $key
        ]);

        if (!$configData) {
            throw new SettingsException(__('Key not found in Config'));
        }

        return $this->respond(
            $response,
            response()
                ->setData($configData)
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
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

        $this->searchCriteria
            ->setPage($page)
            ->setLimit($resultPerPage);

        $settings = $this->settingsRepository->paginate($this->searchCriteria, false);

        return $this->respond(
            $response,
            response()
                ->setData(
                    $settings->toArray()
                )
        );
    }

    /**
     * @param   Request   $request
     * @param   Response  $response
     *
     * @return Response
     * @throws SettingsException
     * @throws ValidationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function set(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'key'   => 'required',
            'value' => 'required'
        ]);

        $customResponse = $this->updateSettingsService->update($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
